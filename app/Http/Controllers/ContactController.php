<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Settings\Settings;
use App\Mail\UserMessageMail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $contactdetail = Settings::all();
        return view('contact-us',compact('contactdetail'));
    }


    /**
     * Send Mail to admin.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
     public function sendMessage(Request $request)
     {
     	$this->validate($request,[
     		'name'=>'required',
     		'email'=>'required|email',
     		'contact_number'=>'nullable|numeric',
     		'message'=>'required'
     	]);

     	$input = $request->all();
        $setting = settings();
        if(isset($setting[0]->email) && $setting[0]->email)
        {
            $input=$request -> except('_token');
            Contact::create($input);
            Mail::to($setting[0]->email)->send(new UserMessageMail($input));
            return redirect()->back()->with('success','Message has been Sent Successfully');
        }
        else
        {
            return redirect()->back()->with('error','Error, Please Contact Us');
        }

    }

}
