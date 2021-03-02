<?php

namespace App\Http\Requests\Admin\UserInvitationLink;

use App\Http\Requests\Request;

/**
 * Class StoreUserInvitationLinkRequest.
 */
class StoreUserInvitationLinkRequest extends Request
{
    /**
     * Determine if the UserInvitationLink is authorized to make this request.
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
    		'email' => 'required|email|unique:send_user_invite_links,email',
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
