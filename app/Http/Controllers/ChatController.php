<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\Friend;
use App\User;
use App\Notifications\MessageNotification;
use Auth;
use Cache;

class ChatController extends Controller
{

	public function friends()
	{
		$friends = Auth::user()->friends();
		return view('chat.friends')->withFriends($friends);
	}
	public function deleteFriends($id)
	{       
		Friend::where('user_id',Auth::user()->id)->where('friend_id',$id)->delete();
		Friend::where('friend_id',Auth::user()->id)->where('user_id',$id)->delete();
		return redirect()->route('friends')->with('success','Friend Removed From Network Successfully');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$friends = Auth::user()->friends();
    	if(isset($friends) && count($friends)>0){
    		foreach ($friends as $key => $fri) {
    			if($key==0){

    				$id=$fri->id;
    			}
    		}
    		$friend = User::find($id);
    		return view('chat.index',compact('friend','friends'));

    	}else{
    		return view('chat.index')->withFriends($friends);

    	}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
    	$friend = User::find($id);
    	$notification = $friend->unreadNotifications;
    	foreach ($notification as $key => $value) {
    		if(snake_case(class_basename($value->type))=='message_notification' && $value->notifiable_id){
    			$fid = $value->id;
    			$friend->unreadNotifications->where('id', $fid)->markAsRead();
    		}
    	}
    	$friends=[];
    	$userFriend=Friend::where('user_id',Auth::user()->id)->where('friend_id',$id)->first();
    	if(!isset($userFriend)){
    		Friend::create([
    			'user_id' => Auth::user()->id,
    			'friend_id' => $id
    		]);

            // $input['user_id']=Auth::user()->id;
            // $input['message']='';
            // $input['receiver_id']=$id;
            // $message=auth()->user()->messages()->create($input);
    	}
    	$getfriends = Auth::user()->friends();
    	foreach ($getfriends as $key => $value) {

    		$friends_status=Friend::where('user_id',$value->id)->where('friend_id',Auth::user()->id)->first();
    		$friends[]=$value;
    	}

    	return redirect()->route('user-chat',$id);
      // return view('chat.show',compact('friend','friends'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }

    public function getChat($id) {

    	$notification = auth()->user()->unreadNotifications;
    	foreach ($notification as $key => $value) {
    		if(snake_case(class_basename($value->type))=='message_notification' && $value->notifiable_id==Auth::user()->id)
    			$id = $value->id;
    		auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    	}

    	$chats = Chat::leftjoin('users as u','u.id','=','chats.user_id')->where(function ($query) use ($id) {
    		$query->where('user_id', '=', Auth::user()->id)->where('friend_id', '=', $id);
    	})->orWhere(function ($query) use ($id) {
    		$query->where('user_id', '=', $id)->where('friend_id', '=', Auth::user()->id);
    	})->get(array('chats.*','u.profile_picture'));

    	return $chats;
    }

    public function sendChat(Request $request) {
    	
    	$user = User::find($request->friend_id);

    	if(!Cache::has('user-is-online'.$request->friend_id)){
    		$user->notify(new MessageNotification($request->chat,Auth::user()));
    	}

    	Chat::create([
    		'user_id' => $request->user_id,
    		'friend_id' => $request->friend_id,
    		'chat' => $request->chat
    	]);
    	$chat = Chat::latest()->first();
    	$date = date("Y-m-d H:i:s",strtotime($chat->created_at));

    	return ["date"=>$date,'profile_picture'=>$chat->chatUser->profile_picture];
    }

    public function getFriendProfileImage($id){
    	return User::find($id);
    }
    public function searchFriends(Request $request){
    	$input=$request->all();
    	$search=$input['search'];
    	$users = User::where('users.first_name','LIKE','%'.$search.'%')
    	->orWhere('users.last_name', 'LIKE', '%'.$search.'%')
    	->orWhere('users.username', 'LIKE', '%'.$search.'%')
    	->pluck('id')->toArray();

    	$friends=Friend::where('user_id',Auth::user()->id)->whereIn('friend_id', $users)->get();
    	$array=[];
    	foreach ($friends as $key => $value) {
    		$array[]=$value->userFriend;
    	}	
    	return $array;
    	// $data='';
    	// foreach ($friends as $key => $fri) 
    	// {

    	// 	$data .='<div class="chat chat-1">
    	// 	<a href="'.route('chat.show',$fri->friend_id).'" class="panel-block">
    	// 	<div class="img-wrap">';
    	// 	if(isset($fri->userFriend->profile_picture) && !empty($fri->userFriend->profile_picture))
    	// 	{
    	// 		$data .='<img src="'.asset('public/img/profile_picture/'.$fri->userFriend->profile_picture.'').'" width="50" height="50">';
    	// 	}else{
    	// 		$data .='<img src="'.asset('public/front/images/196.jpg').'" alt="Profile Picture" width="50" height="50">';
    	// 	}
    	// 	$data .='</div>
    	// 	<div class="name">
    	// 	<h2>'.$fri->userFriend->username.'</h2>
    	// 	<span class="status"><onlineuser v-bind:friend="'.$fri.'" v-bind:onlineusers="onlineUsers"></onlineuser>
    	// 	</span>
    	// 	</div>
    	// 	<div class="time">';
    	// 	if(count($fri->userFriend->unreadNotifications->where('type','App\Notifications\MessageNotification')))
    	// 	{
    	// 		$data .='<span class="number">
    	// 		'.count($fri->userFriend->unreadNotifications->where('type','App\Notifications\MessageNotification')).'
    	// 		</span>';
    	// 	}
    	// 	$data .='</div></a></div>';
    	// }
    	// return $data;
    }
}
