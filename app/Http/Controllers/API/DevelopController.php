<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Explore\Explore;
use App\Models\ExploreRequest\ExploreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExploreRequestMail;
use Auth;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Validator;

class DevelopController extends ResponseController
{
	protected $upload_path;
	protected $storage;

	public function __construct()
	{
		$this->upload_path = 'documents'.DIRECTORY_SEPARATOR.'explore_cv'.DIRECTORY_SEPARATOR;

		$this->storage = Storage::disk('public');
	}

	public function index(Request $request)
	{
		$input=$request->all();

		if(isset($input['search']) && $input['search']!=''){

			$search=$input['search'];
			$develop=Explore::orWhere('title', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%')->orderby('created_at' ,'DESC')->paginate(25);
			if(count($develop)==0){
				return $this->sendSuccess('Develop not available for your search');
			}
		}else{
			$develop = Explore::where('status',1)->orderby('created_at' ,'DESC')->paginate(25);
		}
		return $this->sendResponse($develop);
	}

	public function applicationForm(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required',
		]);
		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		$title = $request->all()['title'];
		$title=str_replace('-',' ',$title);
		$develop=Explore::Where('title',$title)->first();
		if(isset($develop))
		{
			return $this->sendResponse($develop);
		}
		else
		{
			return $this->sendSuccess('Develop is not Available');
		}
		
	}

	public function sendApplication(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'=>'required',
			'phone'=>'required|numeric',
			'cover_letter'=>'required',
		],[
			'name.required' => 'The Name field is required',
			'phone.required' => 'The Contact number field is required',
			'phone.numeric' => 'The Contact number must be Numeric',
			'cover_letter.required' => 'The Message field is required',
		]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors());
    	}

		$input=$request->except('_token');
		$setting = settings();
    	$user = $request->user();
		if(isset($setting[0]->email) && $setting[0]->email)
		{
       
			$input['profile_name']=$user->first_name.' '.$user->last_name;
			$input['email']=$user->email;
			$input['user_id']=$user->id;
			ExploreRequest::create($input);
			$this->sendExploreRequestMail($input);
			return $this->sendSuccess('Develop Request Sent successfully');
		}
		else
		{
			return $this->sendError('Error, Please Contact Us');
		}
	}

	public function sendExploreRequestMail($userdata)
	{
    	// Send mail to User
		$setting = settings();
		Mail::to($setting[0]->email)->send(new ExploreRequestMail($userdata));
	}


}
