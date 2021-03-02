<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\User;
use Auth; 
use App\Models\Invites\Invites;
use App\Models\Settings\Settings;
use App\Mail\ReferMessageMail;
use Validator;

class ReferController extends ResponseController
{
	public function index()
	{
		$contactdetail = Settings::all();
		return $this->sendResponse($contactdetail);
	}

	/**
     * Send Refer Apllication Form.
     *
     */
	public function sendMessage(Request $request)
	{

		$validator = Validator::make($request->all(),[
			'name'       =>'required',
			'email'      =>'required|email|unique:invites,email',
			'refer_code' => 'required',
		]);
		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input = $request->all();
		$token = uniqid();
		Invites::create([
			'user_id' => Auth::user()->id,
			'name'    => $input['name'],
			'email'   => $input['email'],
			'message' => $input['message'],
			'token'   => $input['refer_code'],
		]);

		Mail::to($input['email'])->send(new ReferMessageMail($input,$token));
		return $this->sendSuccess('Refer to a Friend has been Sent Successfully');
	}

	/**
     * Register Friend.
     *
     */
	public function registerfriend($token)
	{
		$token = Invites::where('token',$token)->first();
		if(empty($token))
		{
			return $this->sendError('Your Referral Code has Expired!');
		}
		else
		{
			return $this->sendSuccess('Please Login');
		}
	}
}
