<?php

namespace App\Http\Requests\Admin\Sliders;

use App\Http\Requests\Request;

/**
 * Class UpdateSlidersRequest.
 */
class UpdateSlidersRequest extends Request
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
            // 'image' => 'required',
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
