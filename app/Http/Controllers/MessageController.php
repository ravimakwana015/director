<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Friend;
use App\Events\MessageSent;
use App\Events\PrivateMessageSent;
use App\Notifications\MessageNotification;
use Auth;
use Cache;
use DB;

class MessageController extends Controller
{
    public function __construct()
    {
        $this -> middleware('auth');
    }

    public function privateChat()
    {
        return view('private');
    }

    public function userPrivateChat($id)
    {

        return view('user-private', compact('id'));
    }


    public function chatUsers()
    {
        $users = Message ::where('user_id', Auth ::user() -> id)
            -> orderBy('created_at', 'DESC')
            -> get();

        $notifications = Auth ::user() -> unreadNotifications -> where('type', 'App\Notifications\MessageNotification');


        $fiends = [];
        $fiendids = [];
        foreach ($users as $key => $value) {
            $notificationsCount = [];
            foreach ($notifications as $key => $nvalue) {
                if ($value -> receiver_id == $nvalue -> data['sender_id']) {
                    $notificationsCount[] = $nvalue -> data['sender_id'];
                }
            }
            if (isset($value -> friend)) {
                $value -> friend -> notifycount = count($notificationsCount);
                $fiends[] = $value -> friend;
                $fiendids[] = $value -> receiver_id;
            }
        }

        $fiends = array_unique($fiends);

        $fiendslist = Auth ::user() -> friends();
        $fiendslistids = [];
        foreach ($fiendslist as $key => $fiendsl) {
            $fiendslistids[] = $fiendsl -> id;
        }
        $fiendids = array_unique($fiendids);
        $notinmessage = array_diff($fiendslistids, $fiendids);
        if (count($notinmessage) > 0) {
            foreach ($notinmessage as $key => $value) {
                $user = User ::find($value);
                $notificationsCount = [];
                foreach ($notifications as $key => $nvalue) {
                    if ($user -> id == $nvalue -> data['sender_id']) {
                        $notificationsCount[] = $nvalue -> data['sender_id'];
                    }
                }
                $user -> notifycount = count($notificationsCount);
                $fiends[] = $user;
                $fiendids[] = $user -> id;
            }
        }
        $fiends = array_values($fiends);
        foreach ($fiends as $key => $value) {
//            $isreport = Friend ::where('friend_id', $value -> id) -> first();
            $id=$value -> id;
            $isreport = Friend ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
                -> orWhere(function ($query) use ($id) {
                    $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
                }) -> first();
            if (isset($isreport) && $isreport-> is_report==1) {
                $fiends[$key] -> isreport = $isreport -> is_report;
            } else {
                $fiends[$key] -> isreport = 0;
            }
        }

        return $fiends;
    }

    public function currentUserProfile($id)
    {
        // $isReportUser=Friend::where('friend_id',Auth::user()->id)->where('user_id',$id)->where('is_report',1)->first();

        $isReportUser = Friend ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id]) -> where('is_report', 1)
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]) -> where('is_report', 1);
            }) -> first();
        $friend = User ::find($id);
        return ['friend' => $friend, 'isReportUser' => $isReportUser];

    }

    public function markAsRead($id)
    {
        $notifications = Auth ::user() -> unreadNotifications -> where('type', 'App\Notifications\MessageNotification');

        foreach ($notifications as $key => $nvalue) {

            if ($id == $nvalue -> data['sender_id']) {
                DB ::table('notifications') -> where('id', $nvalue -> id) -> update(['read_at' => Date ::now()]);
            }
        }
    }

    public function deleteUser($id)
    {
        // Message::where('receiver_id',$id)->delete();
        // Message::where('user_id',$id)->delete();
        // Friend::where('friend_id',$id)->delete();
        Message ::where(['user_id' => $id, 'receiver_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'receiver_id' => $id]);
            }) -> delete();
        Friend ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
            }) -> delete();
    }

    public function reportUser(Request $request)
    {

        $input = $request -> all();
        $id = $input['id'];
        $isReportUsers = Friend ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
            }) -> get();

//		$user=Friend::where('friend_id',$input['id'])->where('user_id',Auth::user()->id)->first();
        if (isset($isReportUsers)) {
            foreach ($isReportUsers as $user) {
                $user -> update(['is_report' => 1, 'report_by' => Auth ::user() -> id, 'reason' => $input['reason']]);
            }
        }
    }

    public function fetchMessages()
    {
        return Message ::with('user') -> get();
    }

    public function fetchGroupMessages()
    {
        return Message ::where('receiver_id', null) -> with('user') -> get();
    }

    public function privateMessages(User $user)
    {
        // $privateCommunication= Message::with('user')
        // ->where(['user_id'=> auth()->id(), 'receiver_id'=> $user->id])
        // ->orWhere(function($query) use($user){
        //     $query->where(['user_id' => $user->id, 'receiver_id' => auth()->id()]);
        // })
        // ->get();
        $id = $user -> id;
        $chats = Message ::leftjoin('users as u', 'u.id', '=', 'messages.user_id') -> where(function ($query) use ($id) {
            $query -> where('user_id', '=', Auth ::user() -> id) -> where('receiver_id', '=', $id);
        }) -> orWhere(function ($query) use ($id) {
            $query -> where('user_id', '=', $id) -> where('receiver_id', '=', Auth ::user() -> id);
        }) -> get(array('messages.*', 'u.profile_picture as image'));

        return $chats;
    }

    public function sendMessage(Request $request)
    {


        if (request() -> has('file')) {
            $filename = request('file') -> store('chat');
            $message = Message ::create([
                'user_id' => request() -> user() -> id,
                'image' => $filename,
                'receiver_id' => request('receiver_id')
            ]);
        } else {
            $message = auth() -> user() -> messages() -> create(['message' => $request -> message]);

        }


        broadcast(new MessageSent(auth() -> user(), $message -> load('user'))) -> toOthers();

        return response(['status' => 'Message has been Sent Successfully', 'message' => $message]);

    }

    public function sendPrivateMessage(Request $request, User $user)
    {
        if (request() -> has('file')) {
            $filename = request('file') -> store('chat');
            $message = Message ::create([
                'user_id' => request() -> user() -> id,
                'image' => $filename,
                'receiver_id' => $user -> id
            ]);
        } else {
            $input = $request -> all();
            $input['receiver_id'] = $user -> id;
            $message = auth() -> user() -> messages() -> create($input);
        }

        $receiver = User ::find($user -> id);

        $when = now() -> addSeconds(10);
        $receiver -> notify((new MessageNotification($request -> message, Auth ::user())) -> delay($when));
        // if(!Cache::has('user-is-online'.$user->id)){
        // $receiver->notify(new MessageNotification($request->message,Auth::user()));
        // }

        broadcast(new PrivateMessageSent($message -> load('user'))) -> toOthers();

        return response(['status' => 'Message private sent successfully', 'message' => $message, 'image' => $message -> user -> profile_picture]);

    }

}
