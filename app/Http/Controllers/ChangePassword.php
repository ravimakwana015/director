<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ChangePassword extends Controller
{
    /**
     * @return View
     */
	public function index()
	{
		return view('changepassword.index');
	}

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
	public function update(Request $request)
	{
		$this->validate($request,[
			'username' => 'required|regex:/(^[A-Za-z0-9]+$)+/|unique:users,username,'.Auth::id(),
			'currentpassword'=>'required|min:8',
			'newpassword'=>'required|min:8|required_with:confirmpassword|same:confirmpassword',
			'confirmpassword'=>'required|min:8',
		],
		$messages = [
			'username.required' => 'Enter Your User Name',
			'username.regex' => 'Username Only alphanumeric characters are allowed',
			'currentpassword.required' => 'Enter your Current Password',
			'newpassword.required' => 'Enter Your New Password',
			'newpassword.same' => 'The Password and Confirm Password must be Same',
			'confirmpassword.required' => 'Confirm Your New Password',
		]
	);
		$input = $request->all();

		$forumcategory = User::find($input['id']);
		if(Hash::check($input['currentpassword'],$forumcategory->password))
		{
		    $forumcategory->update([
                'username'  => $input['username'],
                'password' => bcrypt($input['newpassword'])
            ]);
		    return redirect()->back()->with('success','Username and Password Updated Successfully');
        }else{
		    return redirect()->back()->with('error','Current Password Is Wrong !!');
        }
	}
}
