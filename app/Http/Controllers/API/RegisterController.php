<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Exceptions\IncompletePayment;
use App\User;
use App\Models\SendUserInviteLink\SendUserInviteLink;
use App\Models\Invites\Invites;
use App\Models\Refercode\Refercode;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Models\Settings\Settings;
use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use App\Notifications\Registeruser;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Repositories\Admin\User\UserRepository;
use App\Mail\SubscriptionMail;
use App\Mail\SubscriptionCancleMail;
use Carbon\Carbon;
use Auth;
use Validator;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Stripe;
use DB;


class RegisterController extends ResponseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->upload_path = 'img'.DIRECTORY_SEPARATOR.'profile_picture'.DIRECTORY_SEPARATOR;

    	$this->storage = Storage::disk('public');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Uploading profile_picture
    	if (array_key_exists('profile_picture', $data) && !empty($data['profile_picture'])) {
    		$data=$this->uploadImage($data);
    	} 
    	$data['password']=bcrypt($data['password']);
    	//unset($data['_token']);
    	unset($data['confirm_password']);
    	if(isset($data['code'])){
    		$link=SendUserInviteLink::where('code',$data['code'])->where('status',0)->first();
    		if(isset($link)){
    			$link->update([
    				'status'=>1,
    			]);
    			return User::create($data);
    		}else{
                return $this->sendError('Access Code is Incorrect');
    		}
    	}else{
    		return User::create($data);
    	}
    }

    public function signup(Request $request)
    {
    	$plan=MembershipSubscriptionPlan::where('status',1)->get();
    	if(isset($plan) && count($plan)==0){
    		return response()->json(array(
    			'status' => false,
    			'msg' => ['No Subscription Plan Available, Please Contact Admin']
    		));
    	}else{
    		$data=$request->all();
    		
    		$rules = array(
    			'username' => 'required|unique:users,username|regex:/(^[A-Za-z0-9]+$)+/',
    			'first_name' => 'required',
    			'last_name' => 'required',
    			'email' => 'required|email|unique:users,email',
    			'password' => 'required|min:8',
    			'token_data' => 'required',
    			'user_type' => 'required',
    			// 'user_agreement' => 'required|not_in:0',
    			// 'privacy_policy' => 'required|not_in:0',
    		);
    		if(isset($data['user_type']) && $data['user_type']=='Crew'){
    			$rules['crew_type']= 'required';
    		}

    		$messages = [
    			'username.regex' => 'Username Only alphanumeric characters are allowed',
    			'token_data.required' => 'The Access code field is required',
    			'user_agreement.not_in' => 'Please Accept User Agreement',
    			'privacy_policy.not_in' => 'Please Accept Privacy Policy',
    			'crew_type.required' => 'Please Select Sector',
    		];


    		$validator = Validator::make($request->all(), $rules,$messages);

    		if ($validator->fails())
    		{
    			return $this->sendError($validator->errors());
    		}


    		$data['password']=bcrypt($data['password']);
    		$data['refer_code'] = $data['first_name'].rand ( 10000 , 99999 );
    		unset($data['_token']);
    		$data['last_login_at'] = Carbon::now()->toDateTimeString();
    		$data['last_login_ip'] = $request->getClientIp();

    		if(isset($data['token_data']) && !empty($data['token_data']))
    		{
    			$token = User::where('refer_code',$data['token_data'])->first();
    			$tokenadmin = Settings::where('access_coupon',$data['token_data'])->first();

    			if($token == "" && $tokenadmin == "")
    			{
    				return $this->sendError('Please Use Valid Access Code');
    			}
    			else
    			{
    				$user = User::create($data);
    				UsersPersonalityTraits::create([
    					'user_id'=> $user->id,
    					'loneliness'=>5,
    					'entertainment'=>5,
    					'curiosity'=>5,
    					'relationship'=>5,
    					'hookup'=>5,
    				]);
    				if(isset($token))
    				{
    					likeProfile($token->id,$token->id,request()->ip(),'refer');
    				}
    				Auth::loginUsingId($user->id);

    				$user->notify(new Registeruser($user));

    				$success['token'] =  $user->createToken('token')->accessToken;
    				$success['message'] = "Registration Successful";
    				return $this->sendResponse($success);
    			}
    		}else{

    			$error = "Please try again.";
    			return $this->sendError($error, 401);
    		}
    	}
    }

    /**
     * Show All Plans.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function plans()
    {
    	if(Auth::user()->subscribed('main')){
    		return $this->sendError('Already subscribed');
    	}else{

    		Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    		$plans = Stripe\Plan::all();
    		$adminplans = MembershipSubscriptionPlan::all();

    		$data=[
    			'plans'      => $plans,
    			'adminplans' => $adminplans
    		];
    		return $this->sendResponse($data);
    	}
    }

    /**
     * Select Payment method.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPlans(Request $request)
    {
    	$input=$request->all();
    	if(Auth::user()->subscribed('main')){
    		return $this->sendError('Already subscribed');
    	}else{
    		$rules = array(
    			'plan_id' => 'required',
    		);
    		$messages = [
    			'plan_id.required' => 'Plan Id field is required',
    		];
    		$validator = Validator::make($request->all(), $rules,$messages);
    		if ($validator->fails())
    		{
    			return $this->sendError($validator->errors());
    		}
    		Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    		try {
    			$plan = Stripe\Plan::retrieve($input['plan_id']);
    			$data=[
    				'plan'  => $plan,
    			];
    			return $this->sendResponse($data);
    		} catch (\Stripe\Exception\RateLimitException $e) {
    			return $this->sendError($e->getMessage());
    		} catch (\Stripe\Exception\InvalidRequestException $e) {
    			return $this->sendError($e->getMessage());
    		} catch (\Stripe\Exception\AuthenticationException $e) {
    			return $this->sendError($e->getMessage());
    		} catch (\Stripe\Exception\ApiConnectionException $e) {
    			return $this->sendError($e->getMessage());
    		} catch (\Stripe\Exception\ApiErrorException $e) {
    			return $this->sendError($e->getMessage());
    		} catch (Exception $e) {
    			return $this->sendError($e->getMessage());
    		}
    	}
    }

    public function checkCoupon(Request $request)
    {
    	$rules = array(
    		'coupon_code' => 'required',
    		'plan_name' => 'required',
    	);
    	$messages = [
    		'coupon_code.required' => 'Coupon Code is required',
    		'plan_name.required'   => 'Plan Name is required',
    	];
    	$validator = Validator::make($request->all(), $rules,$messages);
    	if ($validator->fails())
    	{
    		return $this->sendError($validator->errors());
    	}

    	$coupon = Settings::all();
    	$setcode = $coupon[0]['discount_coupon'];
    	$applycoupon = $request->all();

    	if($setcode == $applycoupon['coupon_code'])
    	{
    		$user = Auth::user();
    		$subscription=DB::table('subscriptions')->where('user_id',$user->id)->first();
    		if(isset($subscription)){
    			return $this->sendError('Already subscribed');
    		}
    		Auth::user()->update(['status'=>1]);
    		DB::table('subscriptions')->insert([
    			'user_id' => $user->id,
    			'name' => 'mains',
    			'stripe_status' => 'active',
    			'stripe_id' => $user->username,
    			'stripe_plan'=> $applycoupon['plan_name'],
    			'quantity'=> 1,
    			'created_at'=>date('Y-m-d H:i:s'),
    			'updated_at'=>date('Y-m-d H:i:s'),
    		]);
    		DB::table('transactions')->insert([
    			'user_id' =>$user->id,
    			'payment_status'=>1,
    			'amount'=>0,
    			'created_at'=>date('Y-m-d H:i:s'),
    			'updated_at'=>date('Y-m-d H:i:s'),
    		]);
            // Send mail to User
    		Mail::to($user['email'])->send(new SubscriptionMail($user));

    		$success['message'] = "Coupon Code apply successful";
    		return $this->sendResponse($success);

    	}
    	else
    	{
    		return $this->sendError('Please Enter Valid Coupon Code');
    	}
    }

        /**
     * subscribed User to  Plans.
     *
     */
        public function orderPost(Request $request)
        {
        	if(Auth::user()->subscribed('main')){
        		return $this->sendError('Already subscribed');
        	}else{

        		$rules = array(
        			'plan' => 'required',
        			'number' => 'required',
        			'exp_month' => 'required',
        			'exp_year' => 'required',
        			'cvc' => 'required',
        		);
        		$messages = [
        			'plan.required'      => 'Please Plan id',
        			'number.required'    => 'Please Enter Card Number',
        			'exp_month.required' => 'Please Enter Expiry Month Of Card Number',
        			'exp_year.required'  => 'Please Enter Expiry Year Of Card Number',
        			'cvc.required'       => 'Please Enter CVV number of Card Number',
        		];
        		$validator = Validator::make($request->all(), $rules,$messages);
        		if ($validator->fails())
        		{
        			return $this->sendError($validator->errors());
        		}

        		Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        		$user = Auth::user();
        		$input = $request->all();
        		try {
        			$paymentMethod =  \Stripe\PaymentMethod::create([
        				'type' => 'card',
        				'card' => [
        					'number' => $input['number'],
        					'exp_month' => $input['exp_month'],
        					'exp_year' => $input['exp_year'],
        					'cvc' => $input['cvc']
        				]
        			]);
     
        			try {
        				if( $coupon = $request->get('coupon') ) 
        				{
        					$user->newSubscription('main',$input['plan'])->withCoupon($coupon)->create($paymentMethod->id);
        				}
        				else
        				{ 
        					$user->newSubscription('main',$input['plan'])->create($paymentMethod->id);
        				}
        				Auth::user()->update(['status'=>1]);
        				Mail::to($user['email'])->send(new SubscriptionMail($user));
        				$success['message'] = "Subscription has been completed. We look forward to having you on our platform!";
        				return $this->sendResponse($success);

        			} catch (\Stripe\Exception\RateLimitException $e) {
        				return $this->sendError($e->getMessage());
        			} catch (\Stripe\Exception\InvalidRequestException $e) {
        				return $this->sendError($e->getMessage());
        			} catch (\Stripe\Exception\AuthenticationException $e) {
        				return $this->sendError($e->getMessage());
        			} catch (\Stripe\Exception\ApiConnectionException $e) {
        				return $this->sendError($e->getMessage());      
        			} catch (\Stripe\Exception\ApiErrorException $e) {
        				return $this->sendError($e->getMessage());
        			} catch (Exception $e) {
        				return $this->sendError($e->getMessage());
        			}

        		} catch (\Stripe\Exception\RateLimitException $e) {
        			return $this->sendError($e->getMessage());
        		} catch (\Stripe\Exception\InvalidRequestException $e) {
        			return $this->sendError($e->getMessage());
        		} catch (\Stripe\Exception\AuthenticationException $e) {
        			return $this->sendError($e->getMessage());
        		} catch (\Stripe\Exception\ApiConnectionException $e) {
        			return $this->sendError($e->getMessage());      
        		} catch (\Stripe\Exception\ApiErrorException $e) {
        			return $this->sendError($e->getMessage());
        		} catch (Exception $e) {
        			return $this->sendError($e->getMessage());
        		}
        	}
        }
    /**
     * Upload Image.
     *
     * @param array $input
     *
     */
    public function uploadImage($input)
    {


    	if (isset($input['profile_picture']) && !empty($input['profile_picture'])) 
    	{
    		$profile_picture=$input['profile_picture'];

    		$fileName = time().$profile_picture->getClientOriginalName();

    		$this->storage->put($this->upload_path.$fileName, file_get_contents($profile_picture->getRealPath()));

    		$input['profile_picture'] = $fileName;
    		return $input;
    	}
    }



}
