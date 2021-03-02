<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Invites\Invites;
use App\Models\Settings\Settings;
use App\Mail\ReferMessageMail;
use Auth;

class ReferController extends Controller
{

	public function index()
	{
		$contactdetail = Settings::all();
		return view('refer.index',compact('contactdetail'));
	}

	public function sendMessage(Request $request)
	{
		$this->validate($request,[
			'name'=>'required',
			'email'=>'required|email',
			'refer_code' => 'required',
		],[
			'refer_code.required' => 'Access code must be required',
		]);

		$input = $request->all();
		$token = uniqid();
		Invites::create([
			'user_id'  => Auth::user()->id,
			'name' => $input['name'],
			'email' => $input['email'],
			'message' => $input['message'],
			'token' => $input['refer_code'],
		]);

		Mail::to($input['email'])->send(new ReferMessageMail($input,$token));
		return redirect()->route('referfriend')->with('success','Refer to a Friend Sent Successfully');
	}

	public function Reminder($id)
	{
		$invite = Invites::where('id',$id)->first();
		$token = $invite->token;
		$input['email']= $invite->email;
		$input['message']= $invite->message;
		$input['name']= $invite->name;
		$input['refer_code']= $invite->token;
		Mail::to($input['email'])->send(new ReferMessageMail($input,$token));
		return redirect()->route('dashboard')->with('success-refer','Reminder Sent Successfully');
	}
	public function registerfriend($token)
	{
		$token = Invites::where('token',$token)->first();
		if(empty($token))
		{
			return redirect()->route('login')->with('error', 'Your Referral Code has Expired!');
		}
		else
		{
			return view('auth.app');
		}
	}
}
