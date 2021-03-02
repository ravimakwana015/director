<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Like\Like;
use App\Models\Genres\Genres;
use App\Models\Accents\Accents;
use App\Models\UserNetwork\UserNetwork;
use App\Models\Contactinquire\Contactinquire;
use App\Mail\ContactProfileEmail;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\Notifications\LikeNotification;
use App\Notifications\ContactProfileNotification;
use App\Notifications\PersonalityTraits;
use App\User;
use Carbon\Carbon;
use Auth;
use Validator;
use DB;
use Session;
use App\Http\Controllers\API\ResponseController as ResponseController;

class UserController extends ResponseController
{
    /**
     * Show the Profiles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
    	$input =  $request->all();
    	$query = User::query();
    	if(Auth::user()){
    		$query = $query->Where('users.id', '!=',Auth::user()->id)->Where('users.user_type', '!=','Crew');
    	}else{
    		$query = $query->Where('users.user_type', '!=','Crew');
    	}
    	$searchResult = $query->select("users.*",
    		DB::raw("(SELECT COUNT(likes.profile_id)  FROM likes WHERE likes.profile_id=users.id) as likes"))
    	->orderby('likes','DESC')
    	->paginate(40);

    	$count = count($searchResult);
    	$data=['profiles' => $searchResult];
    	return $this->sendResponse($data);
    }

    public function searchProfiles(Request $request)
    {
    	$input =  $request->all();
    	$query = User::query();
    	if(Auth::user()){
    		$query = $query->Where('users.id', '!=',Auth::user()->id);
    	}

    	if(isset($input['usertype'])){
    		if($input['usertype']=='all'){
    			$query = $query->Where('users.user_type', '!=','Crew');
    		}else{
    			$query = $query->Where('users.user_type',$input['usertype']);
    		}
    	}

    	if (isset($input['country']) && $input['country']!='') {
    		$query = $query->Where('users.country', 'LIKE', '%'.$input['country'].'%');
    	}
    	if (isset($input['age']) && $input['age']!='') {
    		$query = $query->Where('users.playing_age', 'LIKE', '%'.$input['age'].'%');
    	}
    	if (isset($input['gender']) && $input['gender']!='') {
    		$query = $query->Where('users.gender', $input['gender']);
    	}
    	if (isset($input['genre']) && $input['genre']!='') {
    		$query = $query->Where('users.genre', 'LIKE', '%'.$input['genre'].'%');
    	}
    	if (isset($input['acent']) && $input['acent']!='') {
    		$query = $query->Where('users.acents', 'LIKE', '%'.$input['acent'].'%');
    	}
    	if (isset($input['height']) && $input['height']!='') {
    		$query = $query->Where('users.height', 'LIKE', '%'.$input['height'].'%');
    	}
    	if (isset($input['eye_colour']) && $input['eye_colour']!='') {
    		$query = $query->Where('users.eye_colour', 'LIKE', '%'.$input['eye_colour'].'%');
    	}
    	if (isset($input['hair_colour']) && $input['hair_colour']!='') {
    		$query = $query->Where('users.hair_colour', 'LIKE', '%'.$input['hair_colour'].'%');
    	}
    	if (isset($input['instrument']) && $input['instrument']!='') {
    		$query = $query->Where('users.instrument', 'LIKE', '%'.$input['instrument'].'%');
    	}
    	if (isset($input['crew_type']) && $input['crew_type']!='') {
    		$query = $query->Where('users.crew_type', 'LIKE', '%'.$input['crew_type'].'%');
    	}
    	if(isset($input['search'])){
    		$search = $input['search'];
    		if(Auth::user()){
    			$query = $query->Where('users.id', '!=',Auth::user()->id);
    		}
    		$query->where(function($fq) use ($search) {
    			$fq->orWhere('users.username', 'LIKE', '%'.explode(' ',$search)[0].'%');
    			$fq->orWhere('users.first_name', 'LIKE', '%' . $search. '%');
    			$fq->orWhere('users.last_name', 'LIKE', '%' . $search. '%');
    		});
    	}
    	$searchResult = $query->select("users.*",
    		DB::raw("(SELECT COUNT(likes.profile_id)  FROM likes WHERE likes.profile_id=users.id) as likes"))
    	->orderby('likes','DESC')
    	->paginate(40);
    	if(count($searchResult)==0){
    		$error = "Sorry, we cannot find a person related to search";
    		return $this->sendError($error, 401);
    	}else{
    		$data=['profiles' => $searchResult];
    		return $this->sendResponse($data);
    	}
    }

    public function getSearchOptions()
    {
    	$userdata = User::groupBy('country')->whereNotNull('country')->pluck('country');
    	$playing_age = User::groupBy('playing_age')->whereNotNull('playing_age')->pluck('playing_age');
    	$heights = User::groupBy('height')->whereNotNull('height')->pluck('height');
    	$eye_colours = User::groupBy('eye_colour')->whereNotNull('eye_colour')->pluck('eye_colour');
    	$hair_colours = User::groupBy('hair_colour')->whereNotNull('hair_colour')->pluck('hair_colour');
    	$instruments = User::groupBy('instrument')->whereNotNull('instrument')->pluck('instrument');
    	$gender = User::groupBy('gender')->whereNotNull('gender')->pluck('gender');
    	$genre = Genres::groupBy('genre')->whereNotNull('genre')->pluck('genre');
    	$acents = Accents::groupBy('accent')->whereNotNull('accent')->pluck('accent');

    	return response()->json([
    		'userdata'		=> $userdata,
    		'playing_age'	=> $playing_age,
    		'gender'		=> $gender,
    		'genre'			=> $genre,
    		'acents'		=> $acents,
    		'heights'		=> $heights,
    		'eye_colours'	=> $eye_colours,
    		'hair_colours'  => $hair_colours,
    		'instruments'	=> $instruments,
    	]);
    }

    public function userProfile(Request $request)
    {
    	$rules = array(
    		'username' => 'required',
    	);
    	$messages = [
    		'username.required' => 'username field is required',
    	];
    	$validator = Validator::make($request->all(), $rules,$messages);
    	if ($validator->fails())
    	{
    		return $this->sendError($validator->errors()->all());
    	}
    	$input=$request->all();

    	$slug=str_replace('-',' ',$input['username']);

    	$user=User::where('username',$slug)->first();
    	if(!isset($user)){
    		$error = "Not a valid username";
    		return $this->sendError($error, 401);
    	}
    	if(Auth::user()){
    		$id=$user->id;
    		$userFriend= UserNetwork::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])
    		->orWhere(function($query) use($id){
    			$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id]);
    		})->first();
    	}else{
    		$userFriend=[];
    	}

    	$ip = request()->ip();
    	$likeusers = [];
    	if(Auth::user()){
    		$liveuser  = Auth::user()->id;
    		$likeusers =Like::where('user_id',$liveuser)->Where('profile_id',$user->id)->Where('like_user_type',$user->user_type)->where('created_at','>',date('Y-m-d'))->first();
    	}
    	$data=[
    		'likeCount'  		=> count($user->likeCount),
    		'galleryCount'  	=> count($user->gallery),
    		'videoCount'  		=> count($user->videoGallery),
    		'user'       		=> $user,
    		'userGallery'   	=> $user->gallery,
    		'userVideoGallery'  => $user->videoGallery,
    		'userPersonality'  => $user->personality,
    		'likeusers'  		=> $likeusers,
    		'userFriend' 		=> $userFriend,
    	];
    	return $this->sendResponse($data);
    }

    public function contactForm(Request $request)
    {
    	$input=$request->all();
    	$rules = array(
    		'name'    => 'required',
    		'subject' => 'required',
    		'mobile'  => 'required|numeric|min:10',
    		'email'   => 'required|email',
    		'message' => 'required',
    		'to_user' => 'required',
    	);
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails())
    	{
    		return $this->sendError($validator->errors()->all());
    	}else {
    		$touser=User::find($input['to_user']);
    		Mail::to($touser->email)->send(new ContactProfileEmail($input,$touser));
    		$msg = $input['name'].' Sent inquiry for your profile';

    		$touser->notify((new ContactProfileNotification($touser,trim($msg))));
    		Contactinquire::create([
    			'user_id'  => $input['to_user'],
    			'name'     => $input['name'],
    			'subject'  => $input['subject'],
    			'mobile'   => $input['mobile'],
    			'email'    => $input['email'],
    			'message'  => $input['message'],
    		]);
    		$success['message'] = "Email Send Successfully";
    		return $this->sendResponse($success);
    	}
    }

    public function unlikeProfile(Request $request)
    {
    	$rules = array(
    		'user_id' => 'required',
    	);
    	$messages = [];
    	$validator = Validator::make($request->all(), $rules,$messages);
    	if ($validator->fails())
    	{
    		return $this->sendError($validator->errors()->all());
    	}
    	$input 	  = $request->all();
    	$user_id  = Auth::user()->id;
    	$userlikes = Like::where('user_id',$user_id)->Where('profile_id',$input['user_id'])->where('created_at','like','%'.date('Y-m-d').'%')->first();
    	if(isset($userlikes))
    	{
    		$userlikes->delete();
    		$likecount=likeCount($input['user_id']);
    		$success['likecount'] = $likecount;
    		return $this->sendResponse($success);
    	}else{
    		$error = 'Already Unlike This User';
    		return $this->sendError($error, 401);
    	}
    }

    public function likeProfile(Request $request)
    {
    	$rules = array(
    		'user_id' => 'required',
    	);
    	$messages = [];
    	$validator = Validator::make($request->all(), $rules,$messages);
    	if ($validator->fails())
    	{
    		return $this->sendError($validator->errors()->all());
    	}
    	$input 	  = $request->all();
    	$user_id  = Auth::user()->id;
    	$likeUser = User::find($input['user_id']);
    	$ip       = request()->ip();
    	if(isset($likeUser)){
    		$userTypeLikes=Like::where('user_id',$user_id)->Where('like_user_type',$likeUser->user_type)->where('created_at','like','%'.date('Y-m-d').'%')->first();

    		if(!isset($userTypeLikes))
    		{
    			$like=likeProfile($user_id,$input['user_id'],$ip,$likeUser->user_type);

    			$likecount=likeCount($input['user_id']);

    			$msg=Auth::user()->first_name.' '.Auth::user()->last_name.' ';
    			$likeUser->notify(new LikeNotification($msg));

    			activity()
    			->causedBy($likeUser)
    			->performedOn($like)
    			->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
    			->log('Like Your Profile');

    			$success['likecount'] = $likecount;
    			return $this->sendResponse($success);
    		}else{
    			$error = 'You Like One '.$likeUser->user_type.' User Already, Please Try Tomorrow.';
    			return $this->sendError($error, 401);
    		}
    	}else{
    		$error = 'User Not Available';
    		return $this->sendError($error, 401);
    	}
    }

    public function increasePersonality(Request $request)
    {
    	$rules = array(
    		'userid' => 'required',
    		'traits' => 'required',
    	);
    	$messages = [];
    	$validator = Validator::make($request->all(), $rules,$messages);
    	if ($validator->fails())
    	{
    		return $this->sendError($validator->errors()->all());
    	}
    	$input=$request->all();
    	$personality=UsersPersonalityTraits::where('user_id',$input['userid'])->first();
    	$pruser = User::find($input['userid']);
    	if(isset($personality))
    	{
    		if($personality->click_by!=''){
    			$oldClickedUser=json_decode($personality->click_by,true);
    			if(isset($oldClickedUser[Auth::id()])){

    				$userClickTraits=$oldClickedUser[Auth::id()];
    				if(!in_array($input['traits'],$userClickTraits)){

    					$traitsCount=$personality[$input['traits']]+1;
    					$oldClickedUser[Auth::id()][]=$input['traits'];
    					$click_by=json_encode($oldClickedUser);
    					$personality->update([
    						$input['traits']=>$traitsCount,
    						'click_by'=>$click_by
    					]);
    				}
    				$msg=Auth::user()->first_name.' '.Auth::user()->last_name.' Improve Your Personality Traits';
    				$pruser->notify(new PersonalityTraits($msg));

    				activity()
    				->causedBy($pruser)
    				->performedOn($personality)
    				->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
    				->log('Improve Your Personality');

    				$success['personality'] = $personality[$input['traits']];
    				return $this->sendResponse($success);
    			}else{
    				$traitsCount=$personality[$input['traits']]+1;
    				$oldClickedUser[Auth::id()][]=$input['traits'];
    				$click_by=json_encode($oldClickedUser);
    				$personality->update([
    					$input['traits']=>$traitsCount,
    					'click_by'=>$click_by
    				]);
    				$msg=Auth::user()->first_name.' '.Auth::user()->last_name.' Improve Your Personality Traits';
    				$pruser->notify(new PersonalityTraits($msg));

    				activity()
    				->causedBy($pruser)
    				->performedOn($personality)
    				->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
    				->log('Improve Your Personality');

    				$success['personality'] = $personality[$input['traits']];
    				return $this->sendResponse($success);
    			}
    		}else{
    			$traitsCount=$personality[$input['traits']]+1;
    			$click_by=json_encode(array(Auth::id()=>array($input['traits'])));
    			$personality->update([
    				$input['traits']=>$traitsCount,
    				'click_by'=>$click_by
    			]);
    		}
    		$msg=Auth::user()->first_name.' '.Auth::user()->last_name.' Improve Your Personality Traits';
    		$pruser->notify(new PersonalityTraits($msg));
    		activity()
    		->causedBy($pruser)
    		->performedOn($personality)
    		->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
    		->log('Improve Your Personality');
    		$success['personality'] = $personality[$input['traits']];
    		return $this->sendResponse($success);
    	}else{
    		$error = 'Personality Not Found';
    		return $this->sendError($error, 401);
    	}

    }
}
