<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;

class AdminLoginController extends Controller
{
	public function __construct(){
		$this->middleware('guest:admin', ['except' => ['logout']]);
	}
	public function showLoginForm(){
		return view('admin.auth.admin-login');
	}

	public function login(Request $request){

		$this->validate($request,[
			'email'=>'required|email',
			'password'=>'required|min:6'
		]);

		$admin_user = DB::table('admins')
		->where('email', '=', $request->email)
		->first();
		if (isset($admin_user) && Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$request->remember)){
			
			Session::put('user_id', $admin_user);
			return redirect()->route('admin.home');
		}
		Session::flash('error', "Invalid Email or Password, Please try again" );
		return redirect()->back()->withInput($request->only('email','remember'));
	}

	public function logout(Request $request)
	{
		Auth::guard('admin')->logout();
		$request->session()->invalidate();
		return redirect('/admin/dashboard');
	}
}
