<?php

namespace App\Http\Requests\Admin\Career;

use App\Http\Requests\Request;

/**
 * Class StoreCareerRequest.
 */
class StoreCareerRequest extends Request
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
    		'title'       => 'required|regex:/(^[A-Za-z0-9 -]+$)+/',
            'description' => 'required',
            'country' => 'required',
            'icon'        => 'required|image',
            'job_type'    => 'required',
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
            'title.regex' =>"Only alphanumeric characters are allowed"
        ];
    }
}
