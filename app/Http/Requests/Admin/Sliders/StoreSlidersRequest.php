<?php

namespace App\Http\Requests\Admin\Sliders;

use App\Http\Requests\Request;

/**
 * Class StoreSlidersRequest.
 */
class StoreSlidersRequest extends Request
{
    /**
     * Determine if the Sliders is authorized to make this request.
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
            'title' => 'required',
    		'image' => 'required',
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
