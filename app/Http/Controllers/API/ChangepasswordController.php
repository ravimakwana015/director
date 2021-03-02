<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\User;
use Auth;
use Validator;

class ChangepasswordController extends ResponseController
{
	/**
     * Update User Password.
     *
     */
	public function update(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'id' => 'required',
			'username' => 'required|regex:/(^[A-Za-z0-9]+$)+/|unique:users,username,'.Auth::id(),
			'newpassword' =>'required|min:8|required_with:confirmpassword|same:confirmpassword',
			'confirmpassword'=>'required|min:8',
		],
		$messages = [
			'username.regex'  => 'Username Only alphanumeric characters are allowed',
			'newpassword.required' => 'The Password field is required',
			'newpassword.same' => 'The Password and Confirm Password must be Same',
			'confirmpassword.required' => 'The Confirm Password field is required',
			'id.required' => 'The UserId field is required',
		]
	);
		if($validator->fails()){
			return $this->sendError($validator->errors());
		}

		$input = $request->all();
		$userPassword = User::find($input['id']);

		$userPassword->update([
			'username'  => $input['username'],
			'password'  => bcrypt($input['newpassword'])
		]);

		return $this->sendSuccess('Username and Password Update Successful !!');
	}
}