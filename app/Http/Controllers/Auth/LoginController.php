<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
    	$this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request){

    	$rules = array('login'=>'required','password'=>'required|min:6');
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails())
    	{
    		return response()->json(array(
    			'status' => false,
    			'msg' => $validator->errors()->all()
    		));
    	}
    	else
    	{

    		$login = request()->input('login');

    		$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    		request()->merge([$fieldType => $login]);

    		if(filter_var($request->email, FILTER_VALIDATE_EMAIL))
    		{
    			if (Auth::guard()->attempt(['email'=>$request->email,'password'=>$request->password],$request->remember))
    			{
    				Auth::user()->update([
    					'last_login_at' => Carbon::now()->toDateTimeString(),
    					'last_login_ip' => $request->getClientIp()
    				]);
//    				if(Auth::user()->subscribed('main') || Auth::user()->subscribed('mains')){
//    					Auth::user()->update(['status'=>1]);
//    				}else{
//    					Auth::user()->update(['status'=>0]);
//    				}
    				return response()->json(array(
    					'status' => true,
    					'msg' => 'Login Successful'
    				));
    			}
    			else
    			{
    				return response()->json(array(
    					'status' => false,
    					'msg' => 'Invalid Email or Password, Please try again'
    				));
    			}
    		}
    		else
    		{
    			if (Auth::guard()->attempt(['username'=>$request->username,'password'=>$request->password],$request->remember))
    			{
    				Auth::user()->update([
    					'last_login_at' => Carbon::now()->toDateTimeString(),
    					'last_login_ip' => $request->getClientIp()
    				]);
//    				if(Auth::user()->subscribed('main') || Auth::user()->subscribed('mains')){
//    					Auth::user()->update(['status'=>1]);
//    				}else{
//    					Auth::user()->update(['status'=>0]);
//    				}
    				return response()->json(array(
    					'status' => true,
    					'msg' => 'Login Successful'
    				));
    			}
    			else
    			{
    				return response()->json(array(
    					'status' => false,
    					'msg' => 'Invalid Username or Password , Please try again.'
    				));
    			}
    		}
    	}
    }
}
