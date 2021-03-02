<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Models\UserNetwork\UserNetwork;
use App\Models\UserFeed\UserFeed;
use App\Models\UserFeedsLikes\UserFeedsLikes;
use App\Models\UserFeedComments\UserFeedComments;
use App\Notifications\FriendRequestNotification;
use App\Notifications\FriendFeedUpdateNotification;
use Auth;
use Validator;
use App\Http\Controllers\API\ResponseController as ResponseController;

class UserNetworkController extends ResponseController
{

	/**
     * Associated Repository Model.
     */
	protected $upload_path;
	protected $storage;

    /**
     *
     */
    public function __construct(User $model)
    {
    	$this->model = $model;

    	$this->upload_path = 'img'.DIRECTORY_SEPARATOR.'feed_images'.DIRECTORY_SEPARATOR;

    	$this->storage = Storage::disk('public');
    }




	/**
   * Add User into Network.
   *
  **/
	public function addFriend(Request $request)
	{
		$rules = array(
			'friend_id' => 'required',
		);
		$messages = [
			'friend_id.required' => 'Friend Id field is required',
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$id=$input['friend_id'];
		$frnd=User::find($id);
		if(isset($frnd)){
			$userFriend= UserNetwork::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])
			->orWhere(function($query) use($id){
				$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id]);
			})->first();

			if(!isset($userFriend)){
				$userFriend=UserNetwork::create([
					'user_id'   => Auth::id(),
					'friend_id' =>  $input['friend_id'],
				]);
				$username=str_replace(' ', '-', strtolower(Auth::user()->username));

				$msg='<a href="'.url('profile-details/'.$username).'">'.Auth::user()->first_name.' '.Auth::user()->last_name.' Sent you a friend request </a>';
				$subject='You Have a New Friend Request From'.' '.Auth::user()->first_name.' '.Auth::user()->last_name;
				$friendUser=User::find($input['friend_id']);
				$when = now()->addMinutes(2);
			// $friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user()))->delay($when));
				$friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user())));
				$userFriend= UserNetwork::find($userFriend->id);
			}
			$success[]['message'] = "Request Sent successful";
			$success[]['friend'] = $userFriend;
			return $this->sendResponse($success);
		}else{
			$error = 'This User Not available';
			return $this->sendError($error, 401);
		}
	}

  /**
   * Confirm Friend Request.
   *
  **/
  public function confirmFriend(Request $request)
  {
  	$rules = array(
  		'friend_id' => 'required',
  	);
  	$messages = [
  		'friend_id.required' => 'Friend Id field is required',
  	];
  	$validator = Validator::make($request->all(), $rules,$messages);
  	if ($validator->fails())
  	{
  		return $this->sendError($validator->errors()->all());
  	}
  	$input=$request->all();
  	$id=$input['friend_id'];

  	$userFriend= UserNetwork::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])
  	->orWhere(function($query) use($id){
  		$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id]);
  	})->first();

  	if(isset($userFriend)){
  		$userFriend->update(['status'=>1]);
  		$username=str_replace(' ', '-', strtolower(Auth::user()->username));
  		$msg='<a href="'.url('profile-details/'.$username).'">'.Auth::user()->first_name.' '.Auth::user()->last_name.' Accepted your friend request </a>';
  		$subject=Auth::user()->first_name.' '.Auth::user()->last_name.' Accepted your friend request';
  		$friendUser=User::find($input['friend_id']);
  		$when = now()->addMinutes(2);
  		$friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user())));
  		$userFriend= UserNetwork::find($userFriend->id);

  		$success[]['message'] = "Request Accepted successfully";
  		$success[]['friend'] = $userFriend;
  		return $this->sendResponse($success);

  	}else{
  		$error = 'This User Not available';
  		return $this->sendError($error, 401);
  	}
  }

  /**
   * Remove Friend From Network.
   *
  **/
  public function removeFriend(Request $request)
  {
  	$rules = array(
  		'friend_id' => 'required',
  	);
  	$messages = [
  		'friend_id.required' => 'Friend Id field is required',
  	];
  	$validator = Validator::make($request->all(), $rules,$messages);
  	if ($validator->fails())
  	{
  		return $this->sendError($validator->errors()->all());
  	}
  	$input=$request->all();
  	$id=$input['friend_id'];

  	$userFriend= UserNetwork::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])
  	->orWhere(function($query) use($id){
  		$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id]);
  	})->first();

  	if(isset($userFriend)){
  		$userFriend->delete();
  		$username=str_replace(' ', '-', strtolower(Auth::user()->username));
  		$msg='<a href="'.url('profile-details/'.$username).'">'.Auth::user()->first_name.' '.Auth::user()->last_name.' Remove From Friend list </a>';
  		$subject=Auth::user()->first_name.' '.Auth::user()->last_name.'  Remove From Friend list';
  		$friendUser=User::find($input['friend_id']);
  		$when = now()->addMinutes(2);
  		$friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user())));
  		$userFriend= UserNetwork::find($userFriend->id);
  		$success[]['message'] = "Remove From Friend list successful";
  		$success[]['friend'] = $userFriend;
  		return $this->sendResponse($success);
  	}else{
  		$error = 'This User Not available';
  		return $this->sendError($error, 401);
  	}
  }

	/**
   * Display User Network.
   *
  **/
	public function myNetwork(Request $request)
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
			$error = 'This User Not available';
			return $this->sendError($error, 401);
		}
		if(Auth::user() && Auth::id()==$user->id || is_friend($user->id)){
			$ids=[];
			foreach ($user->networkFriends() as $key => $value) {
				$ids[]=$value->id;
			}
			$ids[]=$user->id;

			if(isset($ids)){
				$feeds=UserFeed::whereIn('user_id',$ids)
				->orderBy('created_at','desc')->paginate(50);
			}else{
				$feeds=[];
			}
			$success[]['feeds'] = $feeds;
			$success[]['user'] = $user;
			return $this->sendResponse($success);
		}else{
			$error = 'You can see only your Friends Feed';
			return $this->sendError($error, 401);
		}
	}

	/**
   * Add user Status.
   *
  **/
	public function addUserStatus(Request $request)
	{
		$input=$request->all();

		if(empty($input['user_feed_image']) && empty($input['feed']) ) {
			$error = 'Please Enter Something';
			return $this->sendError($error, 401);
		}
		$rules = array(
			'user_feed_image' => 'image|mimes:jpeg,png,jpg|max:2000'
		);
		$messages = [
			'user_feed_image.mimes' => 'Only jpeg,png and jpg images are allowed',
			'user_feed_image.max' => 'Sorry! Maximum allowed size for an image is 2MB'
		];
		$validator = Validator::make($request->all(), $rules,$messages);

		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		else
		{

			$input['user_id']=Auth::id();
			$input['feed_type']='status';
			$fileName = '';
			if (isset($input['user_feed_image']) && !empty($input['user_feed_image']))
			{
				$image=$input['user_feed_image'];

				$fileName = time().$image->getClientOriginalName();

				$this->storage->put($this->upload_path.$fileName, file_get_contents($image->getRealPath()));
				$input['properties'] = json_encode(['image' => $fileName]);
			}
			$feed=UserFeed::create($input);

			$message=' Update New Status';
			if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
			{
				$this->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
			}
			if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
			{
				$this->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
			}

			activity()
			->causedBy(Auth::user())
			->performedOn($feed)
			->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
			->log('added new status');

			$success['message'] = 'Status Added successful';
			$success['feed'] = $feed;
			$success['image'] = $fileName;
			return $this->sendResponse($success);
		}
	}
	/**
   * Delete user Status.
   *
  **/
	public function deletePost(Request $request)
	{
		$rules = array(
			'feed_id' => 'required|numeric',
		);
		$messages = [
			'feed_id.required' => 'Feed Id field is required'
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails()) {
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$feed=UserFeed::where('id',$input['feed_id'])->first();
		if(isset($feed)){
			$feed->delete();
			$success['message'] = 'Post Deleted successful';
			return $this->sendResponse($success);
		}else{
			$error = 'Feed Not available';
			return $this->sendError($error, 401);
		}
	}
	/**
   * get user Status.
   *
  **/
	public function getPost(Request $request)
	{
		$rules = array(
			'feed_id' => 'required|numeric',
		);
		$messages = [
			'feed_id.required' => 'Feed Id field is required'
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails()) {
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$feed=UserFeed::find($input['feed_id']);
		if(isset($feed)){
			$success[]['feed'] = $feed;
			return $this->sendResponse($success);
		}else{
			$error = 'Feed Not available';
			return $this->sendError($error, 401);
		}
	}
	/**
   * get user Status.
   *
  **/
	public function updatePost(Request $request)
	{
		$input=$request->all();

		$rules = array(
			'feed_id' => 'required|numeric',
			'feed' => 'required',
		);
		$messages = [
			'feed.required' => 'Please Write Your Status',
			'feed_id.required' => 'Feed Id field is required'
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails()) {
			return $this->sendError($validator->errors()->all());
		}else{
			$feed=UserFeed::find($input['feed_id']);
			if(isset($feed)){
				$feed->update([
					'feed' =>  $input['feed']
				]);
				$feed=UserFeed::find($input['feed_id']);
				$success[]['feed'] = $feed;
				return $this->sendResponse($success);
			}else{
				$error = 'Feed Not available';
				return $this->sendError($error, 401);
			}
		}
	}

	/**
   * Like Network Friend Status.
   *
  **/
	public function likePost(Request $request)
	{
		$rules = array(
			'feed_id' => 'required',
		);
		$messages = [
			'feed_id.required' => 'Feed Id field is required',
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$input['friend_id']=Auth::id();
		$feed=UserFeed::find($input['feed_id']);
		if(isset($feed)){
			$alreadyLikes=UserFeedsLikes::where('feed_id',$input['feed_id'])->where('friend_id',$input['friend_id'])->first();
			if(isset($alreadyLikes)){
				$error = 'You already likes this Feed';
				return $this->sendError($error, 401);
			}
			$feed=UserFeedsLikes::create($input);
			$likes=UserFeedsLikes::where('feed_id',$input['feed_id'])->count();

			activity()
			->causedBy($feed->post->postOwner)
			->performedOn($feed)
			->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
			->log('Like Your Status');

			$username=str_replace(' ', '-', strtolower(Auth::user()->username));
			$msg='<a href="'.url('profile-details/'.$username).'">'.Auth::user()->first_name.' '.Auth::user()->last_name.' Like Your Status </a>';
			$subject=Auth::user()->first_name.' '.Auth::user()->last_name.' Like Your Status';
			$friendUser=$feed->post->postOwner;
			$when = now()->addMinutes(2);
		// $friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user()))->delay($when));
			if($friendUser->id!=Auth::user()->id){
				$friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user())));
			}

			$success[]['message'] = "Feed Like successful";
			$success[]['likes'] = $likes;
			return $this->sendResponse($success);
		}else{
			$error = 'Feed Not available';
			return $this->sendError($error, 401);
		}
	}

	/**
   * Dislike Network Friend Status.
   *
  **/
	public function disLikePost(Request $request)
	{
		$rules = array(
			'feed_id' => 'required',
		);
		$messages = [
			'feed_id.required' => 'Feed Id field is required',
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$feed=UserFeed::find($input['feed_id']);
		if(isset($feed)){

			$feed=UserFeedsLikes::where('feed_id',$input['feed_id'])->where('friend_id',Auth::id())->delete();
			$likes=UserFeedsLikes::where('feed_id',$input['feed_id'])->count();

			$success[]['message'] = "Feed disLike successful";
			$success[]['likes'] = $likes;
			return $this->sendResponse($success);
		}else{
			$error = 'Feed Not available';
			return $this->sendError($error, 401);
		}
	}

	/**
   * Add Comment on User Post.
   *
  **/
	public function postComment(Request $request)
	{
		$rules = array(
			'comment' => 'required',
			'feed_id' => 'required|numeric',
		);
		$messages = [
			'comment.required' => 'Please Write Your Comment',
			'feed_id.required' => 'Feed Id field is required'
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails()) {
			return $this->sendError($validator->errors()->all());
		}else{
			$input=$request->all();
			$input['friend_id']=Auth::id();
			$feed=UserFeed::where('id','=',$input['feed_id'])->first();

			if(isset($feed)){
				$feedcomment=UserFeedComments::create($input);
				$message=' Commented on Your Status';
				if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
				{
					$this->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
				}
				if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
				{
					$this->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
				}

				activity()
				->causedBy($feedcomment->feedOwner->postOwner)
				->performedOn($feedcomment)
				->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
				->log('Commented on Your Status');

				// $commentData='<li class="media">
				// <a href="#" class="pull-left">';
				// if(isset($feedcomment->commentOwner->profile_picture) && $feedcomment->commentOwner->profile_picture!=''){

				// 	$commentData .='<img src="'. asset('public/img/profile_picture/'.$feedcomment->commentOwner->profile_picture.'').'" alt="profile-pic" id="profile_img" width="80" height="80">';
				// }else{
				// 	$commentData .='<img src="'.asset('public/front/images/no-image-available.png').'" alt="profile-pic" id="profile_img" width="80" height="80">';
				// }
				// $commentData .='</a>
				// <div class="media-body">
				// <strong>'.$feedcomment->commentOwner->first_name.' '.$feedcomment->commentOwner->last_name.'</strong>
				// <span class="text-muted pull-right">';
				// $diff = $feedcomment->created_at->diffForHumans(null, true, true, 1);
				// $commentData .='<small class="text-muted">'.str_replace(['h', 'm'], ['hrs', ' mins'], $diff).'</small>
				// </span>
				// <p>'.$feedcomment->comment.'</p>
				// </div>
				// </li>';
				$comment=UserFeedComments::where('feed_id',$input['feed_id'])->count();

				$success[]['feedcomment'] = $feedcomment;
				// $success[]['commentdata'] = $commentData;
				$success[]['comment'] = $comment;
				return $this->sendResponse($success);
			}else{
				$error = 'Feed Not available';
				return $this->sendError($error, 401);
			}
		}
	}

	/**
   * Display Notification to network users.
   *
  **/
	public function sendNetworkNotification($networks,$message){

		foreach ($networks as $key => $friend) {
			$msg=''.Auth::user()->first_name.' '.Auth::user()->last_name.$message;
			$subject='Your Friend '.Auth::user()->first_name.' '.Auth::user()->last_name.$message;
			// $when = now()->addMinutes(2);
			// $friend->notify((new FriendFeedUpdateNotification($msg,$subject,Auth::user()))->delay($when));
			$friend->notify((new FriendFeedUpdateNotification($msg,$subject,Auth::user())));
		}
	}

	/**
   * Upload User Feed Image.
   *
  **/
	public function uploadFeedImage(Request $request){
		$input=$request->all();
		if (isset($input['user_feed_image']) && !empty($input['user_feed_image']))
		{
			$image=$input['user_feed_image'];

			$fileName = time().$image->getClientOriginalName();

			$this->storage->put($this->upload_path.$fileName, file_get_contents($image->getRealPath()));

			// $img = Image::make($input['profile_picture'])->resize(225, 225);
			// if (!file_exists(public_path() . "/img/profile_picture/225")) {
			// 	mkdir(public_path() . "/img/profile_picture/225", 0777, true);
			// }
			// $img->save(public_path() . "/img/profile_picture".'/225/'.$fileName.'',100);

			$input['properties'] = json_encode(['image' => $fileName]);
			$input['user_id'] = Auth::id();
			$input['feed_type']='status';
			$feed=UserFeed::create($input);

			$message=' Added New Image';
			if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
			{
				$this->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
			}
			if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
			{
				$this->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
			}

			activity()
			->causedBy(Auth::user())
			->performedOn($feed)
			->withProperties(['name' => Auth::user()->first_name.' '.Auth::user()->last_name,'username'=>Auth::user()->username])
			->log('Added New Image');

			return response()->json(array(
				'status' => true,
				'feed' => $feed,
				'image' => $fileName
			));
		}
	}


  /**
   * Report Or Block Network user.
   *
  **/
  public function reportNetworkUser(Request $request)
  {
  	$input=$request->all();
  	$id=$input['friend_id'];

  	$userFriend= UserNetwork::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])
  	->orWhere(function($query) use($id){
  		$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id]);
  	})->first();

  	if(isset($userFriend)){
  		$userFriend->update([
  			'report_by'=>Auth::id(),
  			'reason'=>$input['reason'],
  			'status'=>3
  		]);
  		$response = ['status' => true,'friend' => $userFriend];
  	}else{
  		$response = ['status' => false];
  	}
  	return response()->json($response);
  }

}
