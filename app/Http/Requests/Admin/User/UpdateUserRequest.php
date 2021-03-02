<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Request;

/**
 * Class UpdateUserRequest.
 */
class UpdateUserRequest extends Request
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
            'username' => 'required|unique:users,username,'.$this->segment(3),
            'first_name' => 'required',
            'last_name' => 'required',
//            'city' => 'required',
//            'mobile' => 'required|numeric|min:10',
            'email' => 'required|email|unique:users,email,'.$this->segment(3),
            'user_type' => 'required',
            //'verify' => 'required',
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

    	];
    }
}
