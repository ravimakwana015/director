<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use App\Models\SendUserInviteLink\SendUserInviteLink;
use App\Models\Invites\Invites;
use App\Models\Refercode\Refercode;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Models\Settings\Settings;
use App\Models\AccessCode\AccessCode;
use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use App\User;
use App\Notifications\Registeruser;
use Carbon\Carbon;
use Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\SubscriptionMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('guest');
    	$this->upload_path = 'img'.DIRECTORY_SEPARATOR.'profile_picture'.DIRECTORY_SEPARATOR;

    	$this->storage = Storage::disk('public');
    }

    /**
     * User Register From.
     *
     */
    public function userRegisterForm($code)
    {
    	$link=SendUserInviteLink::where('code',$code)->where('status',0)->first();
    	if(isset($link)){
    		return view('auth.register',compact('link'));
    	}else{
    		return redirect()->route('login')->with('error','Access Code is Incorrect');
    	}
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    // 	return Validator::make($data, [
    // 		'username' => 'required|unique:users,username',
    // 		'first_name' => 'required',
    // 		'last_name' => 'required',
    // 		'city' => 'required',
    // 		'mobile' => 'required|numeric|min:10|unique:users,mobile',
    // 		'email' => 'required|email|unique:users,email',
    // 		'password' => 'required|min:8|same:confirm_password',
    // 		'confirm_password' => 'required|min:8',
    // 		'user_type' => 'required',
    // 	]);
    // }

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
    			return redirect()->back()->with('error','Access Code is Incorrect');
    		}
    	}else{
    		return User::create($data);
    	}
    }

    public function userRegister(Request $request)
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
    			'user_agreement' => 'required|not_in:0',
    			'privacy_policy' => 'required|not_in:0',
    		);
    		if(isset($data['user_type']) && $data['user_type']=='Crew'){
    			$rules['crew_type']= 'required';
    		}

    		$messages = [
    			'username.required' => 'Username is required.',
    			'username.regex' => 'Username Only alphanumeric characters are allowed',
    			'first_name.required' => 'First-Name is required',
    			'last_name.required' => 'Last-Name is required.',
    			'email.required' => 'Email-Address is required.',
    			'password.required' => 'Password is required.',
    			'token_data.required' => 'Access Code is required.',
    			'user_type.required' => 'User Type is required.',
    			'user_agreement.not_in' => 'Please Accept our User Agreement.',
    			'privacy_policy.not_in' => 'Please Accept our Privacy Policy',
    			'crew_type.required' => 'Please Select Sector',
    		];


    		$validator = Validator::make($request->all(), $rules,$messages);
    		if ($validator->fails())
    		{
    			return response()->json(array(
    				'status' => false,
    				'msg' => $validator->errors()
    			));
    		}else {


    			$data['password']=bcrypt($data['password']);
    			$data['access_code']=$data['token_data'];
    			$data['refer_code'] = $data['first_name'].rand ( 10000 , 99999 );
    			unset($data['_token']);
    			$data['last_login_at'] = Carbon::now()->toDateTimeString();
    			$data['last_login_ip'] = $request->getClientIp();
    			$data['trial_ends_at'] = now()->addDays(10);
    			if(isset($data['token_data']) && !empty($data['token_data']))
    			{
    				$token = User::where('refer_code',$data['token_data'])->first();
    				$tokenadmin = AccessCode::where('code',$data['token_data'])->where('status',1)->first();

    				if($token == "" && $tokenadmin == "")
    				{
    					return response()->json(array(
    						'status' => false,
    						'msg' => ['Please Use Valid Access Code']
    					));
    				}
    				else
    				{
    					unset($data['user_agreement']);
    					unset($data['privacy_policy']);
    					$user = User::create($data);
    					UsersPersonalityTraits::create([
    						'user_id'=> $user->id,
    						'loneliness'=>5,
    						'entertainment'=>5,
    						'curiosity'=>5,
    						'relationship'=>5,
    						'hookup'=>5,
    					]);
    					$tokenadmin->count=$tokenadmin->count+1;
    					$tokenadmin->save();
    					if(isset($token))
    					{
    						likeProfile($token->id,$token->id,request()->ip(),'refer');
    					}
    					Auth::loginUsingId($user->id);

    					$user->notify(new Registeruser($user));
    					/** Temporary **/
    					Auth::user() -> update(['status' => 1]);
    					DB::table('subscriptions') -> insert([
    						'user_id' => $user->id,
    						'name' => 'main',
    						'stripe_status' => 'active',
    						'stripe_id' => $user ->username,
    						'stripe_plan' => 'Year',
    						'quantity' => 1,
    						'created_at' => date('Y-m-d H:i:s'),
    						'updated_at' => date('Y-m-d H:i:s'),
    					]);
    					DB::table('transactions') -> insert([
    						'user_id' => $user->id,
    						'payment_status' => 1,
    						'amount' => 0,
    						'coupon' => 1,
    						'created_at' => date('Y-m-d H:i:s'),
    						'updated_at' => date('Y-m-d H:i:s'),
    					]);
           				 // Send mail to User
    					Mail::to($user['email']) -> send(new SubscriptionMail($user));
    					/** Temporary **/
    					// echo json_encode(array('status' => 1));

    					return response()->json(array(
    						'status' => true,
    						'msg' => 'Registration Successful'
    					));
    				}
    			}
    			return response()->json(array(
    				'status' => false,
    				'msg' => 'Please try again.'
    			));
    		}


    	}
    }

    /**
     * Upload Image.
     *
     * @param array $input
     *
     * @return array $input
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
