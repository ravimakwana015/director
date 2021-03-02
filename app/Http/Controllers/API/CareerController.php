<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Career\Career;
use App\Models\CareerRequest\CareerRequest;
use App\Mail\CareerRequestMail;
use App\Notifications\LikeNotification;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Auth;
use Validator;

class CareerController extends ResponseController
{

	protected $upload_path;
	protected $storage;

	/**
     * @var User Model
     */
	protected $model;
    /**
     *
     */
    public function __construct()
    {
    	$this->upload_path = 'documents'.DIRECTORY_SEPARATOR.'career_cv'.DIRECTORY_SEPARATOR;

    	$this->storage = Storage::disk('public');
    }
    /**
     * Show the Career.
     *
     */
    public function index(Request $request)
    {
    	$input=$request->all();
    	if(isset($input['search']) && $input['search']!=''){

    		$search=$input['search'];
    		$careers=Career::orWhere('title', 'like', '%'.$search.'%')->orWhere('location', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%')->orderby('created_at' ,'DESC')->paginate(25);
    		if(count($careers)==0){
    			return $this->sendSuccess('Career not available for your search');
    		}

    	}else{
    		$careers=Career::where('status',1)->orderby('created_at' ,'DESC')->paginate(25);
    	}
    	return $this->sendResponse($careers);
    }

    /**
     * Show the Career Apllication Form.
     *
     */
    public function applicationForm(Request $request)
    {

    	$validator = Validator::make($request->all(), [
    		'title' => 'required',
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors());
    	}
    	$jobTitle = $request->all()['title'];

    	$jobTitle=str_replace('-',' ',$jobTitle);
    	$career=Career::Where('title',$jobTitle)->first();
    	return $this->sendResponse($career);
    }

    /**
     * Send Career Apllication Form.
     *
     */
    public function sendApplication(Request $request)
    {

    	$validator = Validator::make($request->all(), [
    		'cover_letter' => 'required',
    		'career_id' => 'required',
    		'cv' => 'required|mimes:pdf,docx'
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors());
    	}
    	$input=$request->all();
    	$setting = settings();
    	$user = $request->user();
    	if(isset($setting[0]->email) && $setting[0]->email)
    	{
           // Uploading Cv
    		if (array_key_exists('cv', $input) && !empty($input['cv'])) {
    			$input=$this->uploadImage($input);
    		}
    		$ip  = request()->ip();
    		$input['user_id']=$user->id;
    		$input['profile_name']=$user->first_name.' '.$user->last_name;
    		$input['email']=$user->email;
    		CareerRequest::create($input);
    		$this->sendCareerRequestMail($input);
    		likeProfile($user->id,$user->id,$ip,'career');

    		$msg='';
    		$user->notify(new LikeNotification($msg));
    		return $this->sendSuccess('Career Request Sent successfully');
    	}
    	else
    	{
    		return $this->sendError('Error, Please Contact Us');
    	}

    }

    public function sendCareerRequestMail($userdata)
    {
    	// Send mail to User
    	$setting = settings();
    	Mail::to($setting[0]->email)->send(new CareerRequestMail($userdata));
    }

    /**
     * Upload Image.
     *
     * @param array $input
     *
     * @return array $input
     */
    public function uploadImage($input)
    {
    	if (isset($input['cv']) && !empty($input['cv']))
    	{
    		$cv=$input['cv'];

    		$fileName = time().$cv->getClientOriginalName();

    		$this->storage->put($this->upload_path.$fileName, file_get_contents($cv->getRealPath()));

    		$input['cv'] = $fileName;
    		return $input;
    	}
    }

}
