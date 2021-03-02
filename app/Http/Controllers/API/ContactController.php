<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Settings\Settings;
use App\Mail\UserMessageMail;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Validator;

class ContactController extends ResponseController
{
    
     /**
     * Send Mail to admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function sendMessage(Request $request)
     {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'contact_number'=>'required|numeric',
            'message'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $input = $request->all();
        $setting = settings();
        if(isset($setting[0]->email) && $setting[0]->email)
        {
            Mail::to($setting[0]->email)->send(new UserMessageMail($input));
            return $this->sendSuccess('Message has been Sent Successfully');
        }
        else
        {
            return $this->sendError('Error, Please Contact Us');
        }

    }

}
