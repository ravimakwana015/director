<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Request;

/**
 * Class StoreUserRequest.
 */
class StoreUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	return [
    		'username' => 'required|unique:users,username',
            'first_name' => 'required',
            'last_name' => 'required',
//            'city' => 'required',
//            'mobile' => 'required|numeric|min:10|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:confirm_password',
            'confirm_password' => 'required|min:8',
            'user_type' => 'required',
            //'verify' => 'required',
            // 'profile_description' => 'required',
            // 'profile_picture' => 'required',
    	];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
    	return [
    		// 'country_id.required' => "Please Select Country"
    	];
    }
}
