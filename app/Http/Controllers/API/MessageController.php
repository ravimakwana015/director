<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Friend;
use App\Events\MessageSent;
use App\Events\PrivateMessageSent;
use App\Notifications\MessageNotification;
use App\Http\Controllers\API\ResponseController as ResponseController;

use Auth;
use Cache;
use DB;
use Validator;

class MessageController extends ResponseController
{
	public function chatWithUser(Request $request)
	{   
		$rules = array(
			'friend_id' => 'required',
		);
		$messages = [
			'friend_id.required' => 'friend id is required',
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$id=$input['friend_id'];
		$friend = User::find($id);
		if(isset($friend)){
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
			}
			$getfriends = Auth::user()->friends();
			foreach ($getfriends as $key => $value) {

				$friends_status=Friend::where('user_id',$value->id)->where('friend_id',Auth::user()->id)->first();
				$friends[]=$value;
			}

			return $this->sendSuccess(['User Added In Friend List Successfully !!']);
		}else{
			return $this->sendError(['User not Found']);
		}
	}

	public function chatUsers()
	{
		$users = Message::where('user_id',Auth::user()->id)
		->orderBy('created_at','DESC')
		->get();

		$notifications=Auth::user()->unreadNotifications->where('type','App\Notifications\MessageNotification');

		$fiends=[];
		$fiendids=[];
		foreach ($users as $key => $value) {
			$notificationsCount=[];
			foreach ($notifications as $key => $nvalue) 
			{
				if($value->receiver_id==$nvalue->data['sender_id']){	
					$notificationsCount[]=$nvalue->data['sender_id'];
				}
			}
			if(isset($value->friend)){
				$value->friend->notifycount=count($notificationsCount);
				$fiends[]=$value->friend;
				$fiendids[]=$value->receiver_id;
			}
		}

		$fiends=array_unique($fiends);
		
		$fiendslist=Auth::user()->friends();
		$fiendslistids=[];
		foreach ($fiendslist as $key => $fiendsl) 
		{
			$fiendslistids[]=$fiendsl->id;	
		}
		$fiendids=array_unique($fiendids);
		$notinmessage=array_diff($fiendslistids,$fiendids);
		if(count($notinmessage)>0){
			foreach ($notinmessage as $key => $value) {
				$user=User::find($value);
				$notificationsCount=[];
				foreach ($notifications as $key => $nvalue) 
				{
					if($user->id==$nvalue->data['sender_id']){	
						$notificationsCount[]=$nvalue->data['sender_id'];
					}
				}
				$user->notifycount=count($notificationsCount);
				$fiends[]=$user;
				$fiendids[]=$user->id;
			}
		}	
		$fiends=array_values($fiends);
		foreach ($fiends as $key => $value) {
			$isreport=Friend::where('friend_id',$value->id)->first();
			if(isset($isreportf->is_report)){
				$fiends[$key]->isreport = $isreportf->is_report;
			}
		}
		$success['fiends'] = $fiends;
		return $this->sendResponse($success);
	}
	public function currentUserProfile($id){
		// $isReportUser=Friend::where('friend_id',Auth::user()->id)->where('user_id',$id)->where('is_report',1)->first();

		$isReportUser= Friend::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])->where('is_report',1)
		->orWhere(function($query) use($id){
			$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id])->where('is_report',1);
		})->first();
		$friend = User::find($id);
		return ['friend'=>$friend,'isReportUser'=>$isReportUser];
	}

	public function privateMessages(Request $request)
	{
		$rules = array(
			'receiver_id' => 'required',
		);
		$messages = [
			'receiver_id.required' => 'receiver id is required',
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		$input=$request->all();
		$id=$input['receiver_id'];
		$chats = Message::leftjoin('users as u','u.id','=','messages.user_id')->where(function ($query) use ($id) {
			$query->where('user_id', '=', Auth::user()->id)->where('receiver_id', '=', $id);
		})->orWhere(function ($query) use ($id) {
			$query->where('user_id', '=', $id)->where('receiver_id', '=', Auth::user()->id);
		})->get(array('messages.*','u.profile_picture as image'));

		$success['chats'] = $chats;
		return $this->sendResponse($success);
	}

	public function sendPrivateMessage(Request $request)
	{
		$rules = array(
			'message' => 'required',
			'receiver_id' => 'required',
		);
		$messages = [
			'receiver_id.required' => 'receiver id is required',
			'message.required'    => 'message is required',
		];
		$validator = Validator::make($request->all(), $rules,$messages);
		if ($validator->fails())
		{
			return $this->sendError($validator->errors()->all());
		}
		if(request()->has('file')){
			$filename = request('file')->store('chat');
			$message=Message::create([
				'user_id' => request()->user()->id,
				'image' => $filename,
				'receiver_id' => $user->id
			]);
		}else{
			$input=$request->all();
			$message=auth()->user()->messages()->create($input);
		}

		$receiver = User::find($input['receiver_id']);

		$when = now()->addSeconds(10);
		$receiver->notify((new MessageNotification($request->message,Auth::user()))->delay($when));
		return $this->sendResponse(['Message private sent successfully']);
		
	}
	public function markAsRead($id){
		$notifications=Auth::user()->unreadNotifications->where('type','App\Notifications\MessageNotification');

		foreach ($notifications as $key => $nvalue) 
		{

			if($id==$nvalue->data['sender_id']){		
				DB::table('notifications')->where('id',$nvalue->id)->update(['read_at' => Date::now()]);
			}
		}
	}
	public function deleteUser($id){
		Message::where(['user_id'=> $id, 'receiver_id'=> Auth::user()->id])
		->orWhere(function($query) use($id){
			$query->where(['user_id' => Auth::user()->id, 'receiver_id' => $id]);
		})->delete();
		Friend::where(['user_id'=> $id, 'friend_id'=> Auth::user()->id])
		->orWhere(function($query) use($id){
			$query->where(['user_id' => Auth::user()->id, 'friend_id' => $id]);
		})->delete();
	}
	public function reportUser(Request $request){
		$input=$request->all();
		$user=Friend::where('friend_id',$input['id'])->where('user_id',Auth::user()->id)->first();
		if(isset($user)){
			$user->update(['is_report'=>1,'report_by'=>Auth::user()->id,'reason'=>$input['reason']]);
		}
	}



	public function fetchMessages()
	{
		return Message::with('user')->get();
	}
	public function fetchGroupMessages()
	{
		return Message::where('receiver_id',null)->with('user')->get();
	}

	public function sendMessage(Request $request)
	{

		if(request()->has('file')){
			$filename = request('file')->store('chat');
			$message=Message::create([
				'user_id' => request()->user()->id,
				'image' => $filename,
				'receiver_id' => request('receiver_id')
			]);
		}else{
			$message = auth()->user()->messages()->create(['message' => $request->message]);

		}


		broadcast(new MessageSent(auth()->user(),$message->load('user')))->toOthers();

		return response(['status'=>'Message has been Sent Successfully','message'=>$message]);

	}

	

}
