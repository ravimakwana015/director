<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Languages\Languages;
use App\Models\Skills\Skills;
use App\Models\Genres\Genres;
use App\Models\Accents\Accents;
use App\Models\RoleTypes\RoleTypes;
use App\Models\Like\Like;
use App\Models\Sliders\Sliders;
use App\Models\Settings\Settings;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Models\Country\Country;
use App\Http\Controllers\UserNetworkController;
use App\Models\PhotoGallery\PhotoGallery;
use App\Models\VideoGallery\VideoGallery;
use App\Models\UserFeed\UserFeed;
use App\Notifications\PersonalityTraits;
use Spatie\Activitylog\Models\Activity;
use App\User;
use Auth;
use Stripe;
use Image;
use Validator;

class EdituserController extends ResponseController
{
	protected $upload_path;
	protected $cv_path;
	protected $gallery_upload_path;
	protected $video_upload_path;
	protected $storage;

	public function __construct()
	{
		$this->upload_path = 'img'.DIRECTORY_SEPARATOR.'profile_picture'.DIRECTORY_SEPARATOR;
		$this->cv_path = 'cv'.DIRECTORY_SEPARATOR;
		$this->gallery_upload_path = 'img'.DIRECTORY_SEPARATOR.'user_gallery'.DIRECTORY_SEPARATOR;
		$this->video_upload_path = 'img'.DIRECTORY_SEPARATOR.'video_gallery'.DIRECTORY_SEPARATOR;
		$this->storage = Storage::disk('public');
	}
	/**
     * Show the User profile.
     *
     *
     */
	public function userProfile()
	{
		$userDetails   = Auth::user();
		$languages     = Languages::all();
		$skills        = Skills::all();
		$genres        = Genres::all();
		$accents       = Accents::all();
		$roletypes     = RoleTypes::all();
		$gallery       = Auth::user()->gallery;
		$videogallery  = Auth::user()->videoGallery;

		$userData = [
			'userDetails' => $userDetails,
			'languages'   => $languages,
			'skills'      => $skills,
			'genres'      => $genres,
			'roletypes'   => $roletypes
		];
		return $this->sendResponse($userData);
	}

	/**
     * Update User profile.
     *
     *
     */
	public function updateProfile(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'first_name'  => 'regex:/^[\pL\s\-]+$/u',
			'last_name'   => 'regex:/^[\pL\s\-]+$/u',
			'city'        => 'nullable|regex:/^[\pL\s\-]+$/u',
			'country'     => 'nullable|regex:/^[\pL\s\-]+$/u',
			'eye_colour'  => 'nullable|regex:/^[\pL\s\-]+$/u',
			'playing_age' => 'nullable|regex:/(^[0-9 -]+$)+/',
			'caption'     => 'nullable|max:120',
		],[
			'first_name.regex' => 'The First Name may only contain letters',
			'last_name.regex'  => 'The Last Name may only contain letters',
			'city.regex'       => 'The City may only contain letters',
			'country.regex'    => 'The Country may only contain letters',
			'eye_colour.regex' => 'The Eye Colour may only contain letters',
			'playing_age.numeric' => 'The Playing Age may only contain Number',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input=$request->except('_token');

		if(isset($input['favourite_directors']) && count($input['favourite_directors'])){
			$input['favourite_directors']=json_encode($input['favourite_directors']);
		}else{
			$input['favourite_directors']=null;
		}
		if(isset($input['top_songs']) && count($input['top_songs'])){
			$input['top_songs']=json_encode($input['top_songs']);
		}else{
			$input['top_songs']=null;
		}
		if(isset($input['top_musicians']) && count($input['top_musicians'])){
			$input['top_musicians']=json_encode($input['top_musicians']);
		}else{
			$input['top_musicians']=null;
		}
		if(isset($input['favourite_films']) && count($input['favourite_films'])){
			$input['favourite_films']=json_encode($input['favourite_films']);
		}else{
			$input['favourite_films']=null;
		}
		if(isset($input['favourite_models']) && count($input['favourite_models'])){
			$input['favourite_models']=json_encode($input['favourite_models']);
		}else{
			$input['favourite_models']=null;
		}
		if(isset($input['favourite_brands']) && count($input['favourite_brands'])){
			$input['favourite_brands']=json_encode($input['favourite_brands']);
		}else{
			$input['favourite_brands']=null;
		}

		if(isset($input['languages'])){
			$userlanguages=[];
			foreach ($input['languages'] as $key => $value) {
				$language=Languages::where('language',ucfirst($value))->first();
				if (!isset($language)) {
					Languages::create(['language'=>ucfirst($value)]);
				}
				$userlanguages[]=ucfirst($value);
			}
			$input['languages']=json_encode($userlanguages);
		}
		if(isset($input['skills'])){
			$userskills=[];
			foreach ($input['skills'] as $key => $value) {
				$skill=Skills::where('skill',ucfirst($value))->first();
				if (!isset($skill)) {
					Skills::create(['skill'=>ucfirst($value)]);
				}
				$userskills[]=ucfirst($value);
			}
			$input['skills']=json_encode($userskills);
		}
		if(isset($input['genre'])){
			$usergenres =[];
			foreach ($input['genre'] as $key => $value) {
				$genre=Genres::where('genre',ucfirst($value))->first();
				if (!isset($genre)) {
					Genres::create(['genre'=>ucfirst($value)]);
				}
				$usergenres[]=ucfirst($value);
			}
			$input['genre']=json_encode($usergenres);
		}
		if(isset($input['acents'])){
			$useracents =[];
			foreach ($input['acents'] as $key => $value) {
				$acents=Accents::where('accent',ucfirst($value))->first();
				if (!isset($acents)) {
					Accents::create(['accent'=>ucfirst($value)]);
				}
				$useracents[]=ucfirst($value);
			}
			$input['acents']=json_encode($useracents);
		}
		if(isset($input['role_type'])){
			$useracents =[];
			foreach ($input['role_type'] as $key => $value) {
				$roletypes=RoleTypes::where('role',ucfirst($value))->first();
				if (!isset($roletypes)) {
					RoleTypes::create(['role'=>ucfirst($value)]);
				}
				$useracents[]=ucfirst($value);
			}
			$input['role_type']=json_encode($useracents);
		}
		$input['eye_colour'] = ucfirst($input['eye_colour']);
        $input['hair_colour'] = ucfirst($input['hair_colour']);

		Auth::user()->update($input);
		return $this->sendSuccess('Profile Updated Successfully');
	}

	/**
     * Upload User Images
     *
     *
     */
	public function uploadPhotos(Request $request, UserNetworkController $network)
	{

		$rules = array(
			'photos'=>'required',
			'photos.*' => 'image|mimes:jpeg,png,jpg|max:5000'
		);
		$messages = [
			'photos.*.required' => 'Please upload an image',
			'photos.*.mimes' => 'Only jpeg,png and jpg images are allowed',
			'photos.*.max' => 'Sorry! Maximum allowed size for an image is 5MB'
		];
		$validator = Validator::make($request->all(), $rules,$messages);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		else
		{
			$galleryCount=PhotoGallery::where('user_id',Auth::user()->id)->get();

			if(isset($galleryCount) && count($galleryCount)<10)
			{
				$input=$request->except('_token');

				if(count($input['photos'])<=10)
				{
					$photos = $input['photos'];
					$imagesdata=[];

					if(count($galleryCount)+count($input['photos']) <= 10){

						foreach ($photos as $key => $photo) {

							$fileName = time().$photo->getClientOriginalName();
							$this->storage->put($this->gallery_upload_path.$fileName, file_get_contents($photo->getRealPath()));
							$gallery=PhotoGallery::create(['user_id'=>Auth::user()->id,'image'=>$fileName]);
							$imagesdata[$gallery->id]=$fileName;
						}
						activity()
						->causedBy(Auth::user())
						->performedOn($gallery)
						->log('Uploaded a new photo');

						$input['properties'] = json_encode(['imagegallery' => $imagesdata]);
						$input['user_id'] = Auth::id();
						$feed=UserFeed::create($input);

						$message=' Upload new images on his/her profile';
						if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
						{
							$network->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
						}
						if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
						{
							$network->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
						}
						$images = [
							'files'   => $imagesdata
						];
						return $this->sendResponse($images);

					}else{
						$images = [
							'msg'    => 'You can upload  10 images Only'
						];
						return $this->sendResponse($images,401);
					}
				}else{
					$images = [
						'msg'    => 'You can upload  10 images Only'
					];
					return $this->sendResponse($images,401);
				}
			}else{
				$images = [
					'msg'    => 'You can upload  10 images Only'
				];
				return $this->sendResponse($images,401);
			}
		}
	}

	/**
     * Delete User Images
     *
     *
     */
	public function deletePhotos(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'id'  => 'required',
		],[
			'id.required' => 'Image id must be required',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input = $request->except('_token');
		PhotoGallery::where('id',$input['id'])->delete();
		return $this->sendSuccess('Image Delete Successfully');
	}

	/**
     * Upload User Videos
     *
     *
     */
	public function uploadVideos(Request $request, UserNetworkController $network)
	{

		$validator = Validator::make($request->all(),[
			'video'=>'required|file|max:10000'
		],[
			'video.max' => 'The video less then or equal to 10mb.'
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		else
		{
			$videoCount=VideoGallery::where('user_id',Auth::user()->id)->get();

			if(isset($videoCount) && count($videoCount)<4)
			{
				$input=$request->except('_token');
				$video=$input['video'];

				$fileName = time().$video->getClientOriginalName();
				$this->storage->put($this->video_upload_path.$fileName, file_get_contents($video->getRealPath()));
				$videoGallery=VideoGallery::create(['user_id'=>Auth::user()->id,'video'=>$fileName]);

				activity()
				->causedBy(Auth::user())
				->performedOn($videoGallery)
				->log('Uploaded a new Video');

				$input['properties'] = json_encode(['video' => $fileName]);
				$input['user_id'] = Auth::id();
				$feed=UserFeed::create($input);

				$message=' Upload new video on his/her profile';
				if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
				{
					$network->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
				}
				if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
				{
					$network->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
				}
				return $this->sendSuccess('Video Uploaded Successfully');
			}
			else
			{
				return $this->sendError('You can upload only 4 Videos');
			}
		}
	}

	/**
     * Delete User Video
     *
     *
     */
	public function deleteVideo(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'id'  => 'required',
		],[
			'id.required' => 'Video id must be required',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input=$request->except('_token');
		VideoGallery::where('id',$input['id'])->delete();
		return $this->sendSuccess('Video Delete Successfully');
	}

	/**
     * Profile Picture Upload
     *
     *
     */
	public function saveProfilePictute(Request $request,UserNetworkController $network)
	{
		$validator = Validator::make($request->all(),[
			'image'=>'required',
		],[
			'image.required' => 'Profile Image must be required',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input=$request->all();

		$data = base64_decode($input['image']);
		$image_name= time().'.png';
		$path = public_path() . "/img/profile_picture/" . $image_name;
		file_put_contents($path, $data);

		$img = Image::make($input['image'])->resize(225, 225);
		if (!file_exists(public_path() . "/img/profile_picture/225")) {
			mkdir(public_path() . "/img/profile_picture/225", 0777, true);
		}
		$img->save(public_path() . "/img/profile_picture".'/225/'.$image_name.'',100);

		Auth::user()->update(['profile_picture'=>$image_name]);

		activity()->causedBy(Auth::user())->log('Changed Profile Picture');

		$input['properties'] = json_encode(['profile_pic' => $image_name]);
		$input['user_id'] = Auth::id();
		$feed=UserFeed::create($input);

		$message=' Upload his/her Profile Picture';
		if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
		{
			$network->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
		}
		if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
		{
			$network->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
		}
		return $this->sendResponse($image_name);
	}

	/**
     * Upload CV
     *
     *
     */
	public function uploadCv(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'cv'=>'required|max:10000|mimes:doc,docx,pdf',
		],[
			'cv.required' => 'cv must be required',
			'cv.mimes' => 'Only doc,docx and pdf files are allowed',
		]);

		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		$input = $request->all();

		$cv = $input['cv'];
		$fileName = time().$cv->getClientOriginalName();
		$this->storage->put($this->cv_path.$fileName, file_get_contents($cv->getRealPath()));
		Auth::user()->update(['cv'=>$fileName]);
		return $this->sendResponse($fileName);
	}
}
