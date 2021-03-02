<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
use Illuminate\View\View;
use Validator;

class UserNetworkController extends Controller
{

    /**
     * Associated Repository Model.
     */
    protected $upload_path;
    protected $storage;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this -> model = $model;

        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'feed_images' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    /**
     * Display User Network.
     *
     * @param $username
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function myNetwork($username, Request $request)
    {
        $slug = str_replace('-', ' ', $username);

        $user = User ::where('username', $slug) -> first();
        if (!isset($user)) {
            return redirect() -> back() -> with('error', 'Not a Valid Link');
        }
        if (Auth ::user() && Auth ::id() == $user -> id || is_friend($user -> id)) {
            $ids = [];
            foreach ($user -> networkFriends() as $key => $value) {
                $ids[] = $value -> id;
            }
            $ids[] = $user -> id;

            if (isset($ids)) {
                $feeds = UserFeed ::whereIn('user_id', $ids)
                    -> orderBy('created_at', 'desc') -> paginate(10);
            } else {
                $feeds = [];
            }
            if ($request -> ajax()) {
                if (count($feeds) == 0) {
                    return view('user.feed_empty');
                } else {
                    return view('user.feed_part', compact('feeds'));
                }
            }
            return view('user.feed', compact('feeds', 'user'));
        } else {
            return redirect() -> back() -> with('error', 'You can see only your Friends Feed');
        }
    }

    /**
     * Add user Status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addUserStatus(Request $request)
    {
        $input = $request -> all();
        if (empty($input['user_feed_image']) && empty($input['feed'])) {
            return response() -> json(array(
                'status' => false,
                'msg' => ['Please Enter Something']
            ));
        }
        $rules = array(
            'user_feed_image' => 'image|mimes:jpeg,png,jpg|max:2000'
        );
        $messages = [
            'user_feed_image.mimes' => 'Only jpeg,png and jpg images are allowed',
            'user_feed_image.max' => 'Sorry! Maximum allowed size for an image is 2MB'
        ];
        $validator = Validator ::make($request -> all(), $rules, $messages);

        if ($validator -> fails()) {
            return response() -> json(array(
                'status' => false,
                'msg' => $validator -> errors() -> all()
            ));
        } else {
            if (Auth ::user() -> status == 1) {
                $input['user_id'] = Auth ::id();
                $input['feed_type'] = 'status';
                $fileName = '';
                if (isset($input['user_feed_image']) && !empty($input['user_feed_image'])) {
                    $image = $input['user_feed_image'];

                    $fileName = time() . $image -> getClientOriginalName();

                    $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($image -> getRealPath()));
                    $input['properties'] = json_encode(['image' => $fileName]);
                }
                $feed = UserFeed ::create($input);

                $message = ' Updated their Status';
                if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                    $this -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
                }
                if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                    $this -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
                }

                activity()
                    -> causedBy(Auth ::user())
                    -> performedOn($feed)
                    -> withProperties(['name' => ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name), 'username' => Auth ::user() -> username])
                    -> log('added new status');

                return response() -> json(array(
                    'status' => true,
                    'feed' => $feed,
                    'image' => $fileName
                ));
            } else {
                return response() -> json(array(
                    'status' => false,
                    'msg' => ['You have not Permission, Your Profile is Not Active']
                ));
            }
        }
    }

    /**
     * Delete user Status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePost(Request $request)
    {
        $input = $request -> all();
        $feed = UserFeed ::where('id', $input['id']) -> delete();
        return response() -> json(array(
            'status' => true
        ));
    }

    /**
     * get user Status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPost(Request $request)
    {
        $input = $request -> all();

        $feed = UserFeed ::find($input['id']);
        if (isset($feed)) {
            return response() -> json(array(
                'status' => true,
                'feed' => $feed
            ));
        } else {
            return response() -> json(array(
                'status' => false
            ));
        }
    }

    /**
     * get user Status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePost(Request $request)
    {
        $input = $request -> all();

        $rules = array(
            'feed' => 'required',
        );
        $messages = [
            'feed.required' => 'Please Write Your Status'
        ];
        $validator = Validator ::make($request -> all(), $rules, $messages);
        if ($validator -> fails()) {
            return response() -> json(array(
                'status' => 3,
                'msg' => $validator -> errors()
            ));
        } else {
            $feed = UserFeed ::find($input['feed_id']);

            $feed -> update([
                'feed' => $input['feed']
            ]);
            $feed = UserFeed ::find($input['feed_id']);
            if (isset($feed)) {
                return response() -> json(array(
                    'status' => true,
                    'feed' => $feed
                ));
            } else {
                return response() -> json(array(
                    'status' => false
                ));
            }
        }
    }

    /**
     * Like Network Friend Status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function likePost(Request $request)
    {
        $input = $request -> all();
        $input['friend_id'] = Auth ::id();
        $feed = UserFeedsLikes ::create($input);
        $likes = UserFeedsLikes ::where('feed_id', $input['feed_id']) -> count();

        activity()
            -> causedBy($feed -> post -> postOwner)
            -> performedOn($feed)
            -> withProperties(['name' => ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name), 'username' => Auth ::user() -> username])
            -> log('Liked Your Status');

        $username = str_replace(' ', '-', strtolower(Auth ::user() -> username));
        $msg = '<a href="' . url('profile-details/' . $username) . '">' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . '  Liked Your Status </a>';
        $subject = ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . '  Liked Your Status';
        $friendUser = $feed -> post -> postOwner;
        $when = now() -> addMinutes(2);
        // $friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user()))->delay($when));
        if ($friendUser -> id != Auth ::user() -> id) {
            $friendUser -> notify((new FriendRequestNotification($msg, $subject, Auth ::user())));
        }

        return response() -> json(array(
            'status' => true,
            'likes' => $likes
        ));
    }

    /**
     * Dislike Network Friend Status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function disLikePost(Request $request)
    {
        $input = $request -> all();
        $feed = UserFeedsLikes ::where('feed_id', $input['feed_id']) -> where('friend_id', Auth ::id()) -> delete();
        $likes = UserFeedsLikes ::where('feed_id', $input) -> count();
        return response() -> json(array(
            'status' => true,
            'likes' => $likes
        ));
    }

    /**
     * Add Comment on User Post.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postComment(Request $request)
    {
        $rules = array(
            'comment' => 'required',
        );
        $messages = [
            'comment.required' => 'Please Write Your Comment'
        ];
        $validator = Validator ::make($request -> all(), $rules, $messages);
        if ($validator -> fails()) {
            return response() -> json(array(
                'status' => false,
                'msg' => $validator -> errors()
            ));
        } else {
            $input = $request -> all();
            $input['friend_id'] = Auth ::id();

            $feedcomment = UserFeedComments ::create($input);
            if ($feedcomment -> feedOwner -> user_id != Auth ::id()) {
                $message = ' Commented on Your Status';
                if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                    $this -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
                }
                if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                    $this -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
                }

                activity()
                    -> causedBy($feedcomment -> feedOwner -> postOwner)
                    -> performedOn($feedcomment)
                    -> withProperties(['name' => ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name), 'username' => Auth ::user() -> username])
                    -> log('Commented on Your Status');
            }
            $commentData = '<li class="media" id="comment_li_' . $feedcomment -> id . '">
			<a href="#" class="pull-left">';
            if (isset($feedcomment -> commentOwner -> profile_picture) && $feedcomment -> commentOwner -> profile_picture != '') {

                $commentData .= '<img src="' . asset('public/img/profile_picture/' . $feedcomment -> commentOwner -> profile_picture . '') . '" alt="profile-pic" id="profile_img" width="80" height="80">';
            } else {
                $commentData .= '<img src="' . asset('public/front/images/no-image-available.png') . '" alt="profile-pic" id="profile_img" width="80" height="80">';
            }
            $commentData .= '</a>
			<div class="media-body">
			<strong>' . $feedcomment -> commentOwner -> first_name . ' ' . $feedcomment -> commentOwner -> last_name . '</strong>
			<span class="text-muted pull-right">';
            $diff = $feedcomment -> created_at -> diffForHumans(null, true, true, 1);
            $commentData .= '<small class="text-muted">' . str_replace(['h', 'm'], ['hrs', ' mins'], $diff) . '</small>
			</span>
			<p>' . $feedcomment -> comment . '</p>
			</div>';
            if (Auth ::user() && $feedcomment -> commentOwner -> id == Auth ::user() -> id) {
                $commentData .= '<div class="open-action-box" onclick="openActionBox(' . $feedcomment -> id . ')">
                 <span>···</span>
                 <div class="action-box action-box_' . $feedcomment -> id . '" style="display: none;">
                     <a href="javascript:;" onclick="deletePostComment(' . $feedcomment -> id . ')">Delete</a>
                 </div>
             </div>';
            }
            $commentData .= '</li>';
            $comment = UserFeedComments ::where('feed_id', $input['feed_id']) -> count();
            return response() -> json(array(
                'status' => true,
                'feedcomment' => $feedcomment,
                'commentdata' => $commentData,
                'comment' => $comment
            ));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePostComment(Request $request)
    {
        $input = $request -> all();
        $feedcomment = UserFeedComments ::find($input['id']);
        $feedcomment -> delete();
        return response() -> json(array('status' => 'true'));
    }

    /**
     * Display Notification to network users.
     *
     **/
    public function sendNetworkNotification($networks, $message)
    {

        foreach ($networks as $key => $friend) {
            $msg = '<a href="' . route('my-network', Auth ::user() -> username) . '">' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() ->
                last_name) . ' ' . $message . '  </a>';
//            $msg = '' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . $message;
            $subject = 'Your Friend ' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . $message;
            // $when = now()->addMinutes(2);
            // $friend->notify((new FriendFeedUpdateNotification($msg,$subject,Auth::user()))->delay($when));
            $friend -> notify((new FriendFeedUpdateNotification($msg, $subject, Auth ::user())));
        }
    }

    /**
     * Upload User Feed Image.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFeedImage(Request $request)
    {
        $input = $request -> all();
        if (isset($input['user_feed_image']) && !empty($input['user_feed_image'])) {
            $image = $input['user_feed_image'];

            $fileName = time() . $image -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($image -> getRealPath()));

            // $img = Image::make($input['profile_picture'])->resize(225, 225);
            // if (!file_exists(public_path() . "/img/profile_picture/225")) {
            // 	mkdir(public_path() . "/img/profile_picture/225", 0777, true);
            // }
            // $img->save(public_path() . "/img/profile_picture".'/225/'.$fileName.'',100);

            $input['properties'] = json_encode(['image' => $fileName]);
            $input['user_id'] = Auth ::id();
            $input['feed_type'] = 'status';
            $feed = UserFeed ::create($input);

            $message = ' Added New Image';
            if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                $this -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
            }
            if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                $this -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
            }

            activity()
                -> causedBy(Auth ::user())
                -> performedOn($feed)
                -> withProperties(['name' => ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name), 'username' => Auth ::user() -> username])
                -> log('Added New Image');

            return response() -> json(array(
                'status' => true,
                'feed' => $feed,
                'image' => $fileName
            ));
        }
    }

    /**
     * Add User into Network.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addFriend(Request $request)
    {
        $input = $request -> all();
        $id = $input['friend_id'];

        $userFriend = UserNetwork ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
            }) -> first();

        if (!isset($userFriend)) {
            $userFriend = UserNetwork ::create([
                'user_id' => Auth ::id(),
                'friend_id' => $input['friend_id'],
            ]);
            $username = str_replace(' ', '-', strtolower(Auth ::user() -> username));

            $msg = '<a href="' . url('profile-details/' . $username) . '">' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . ' Sent You a Friend Request </a>';
            $subject = 'You Have A New Friend Request From' . ' ' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name);
            $friendUser = User ::find($input['friend_id']);
            $when = now() -> addMinutes(2);
            // $friendUser->notify((new FriendRequestNotification($msg,$subject,Auth::user()))->delay($when));
            $friendUser -> notify((new FriendRequestNotification($msg, $subject, Auth ::user())));
            $userFriend = UserNetwork ::find($userFriend -> id);
        }
        $response = ['status' => true, 'friend' => $userFriend];
        return response() -> json($response);
    }

    /**
     * Confirm Friend Request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmFriend(Request $request)
    {
        $input = $request -> all();
        $id = $input['friend_id'];

        $userFriend = UserNetwork ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
            }) -> first();

        if (isset($userFriend)) {
            $userFriend -> update(['status' => 1]);
            $username = str_replace(' ', '-', strtolower(Auth ::user() -> username));
            $msg = '<a href="' . url('profile-details/' . $username) . '">' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . ' Accepted Your Friend Request </a>';
            $subject = ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . ' Accepted Your Friend Request';
            $friendUser = User ::find($input['friend_id']);
            $when = now() -> addMinutes(2);
            $friendUser -> notify((new FriendRequestNotification($msg, $subject, Auth ::user())));
            $userFriend = UserNetwork ::find($userFriend -> id);
            $response = ['status' => true, 'friend' => $userFriend];
        } else {
            $response = ['status' => false];
        }
        return response() -> json($response);
    }

    /**
     * Remove Friend From Network.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFriend(Request $request)
    {
        $input = $request -> all();
        $id = $input['friend_id'];

        $userFriend = UserNetwork ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
            }) -> first();

        if (isset($userFriend)) {
            $userFriend -> delete();
            $username = str_replace(' ', '-', strtolower(Auth ::user() -> username));
            $msg = '<a href="' . url('profile-details/' . $username) . '">' . ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . ' Removed From Friend List </a>';
            $subject = ucfirst(Auth ::user() -> first_name) . ' ' . ucfirst(Auth ::user() -> last_name) . '  Removed From Friend List';
            $friendUser = User ::find($input['friend_id']);
            $when = now() -> addMinutes(2);
            $friendUser -> notify((new FriendRequestNotification($msg, $subject, Auth ::user())));
            $userFriend = UserNetwork ::find($userFriend -> id);
            $response = ['status' => true, 'friend' => $userFriend];
        } else {
            $response = ['status' => false];
        }
        return response() -> json($response);
    }

    /**
     * Report Or Block Network user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reportNetworkUser(Request $request)
    {
        $input = $request -> all();
        $id = $input['friend_id'];

        $userFriend = UserNetwork ::where(['user_id' => $id, 'friend_id' => Auth ::user() -> id])
            -> orWhere(function ($query) use ($id) {
                $query -> where(['user_id' => Auth ::user() -> id, 'friend_id' => $id]);
            }) -> first();

        if (isset($userFriend)) {
            $userFriend -> update([
                'report_by' => Auth ::id(),
                'reason' => $input['reason'],
                'status' => 3
            ]);
            $response = ['status' => true, 'friend' => $userFriend];
        } else {
            $response = ['status' => false];
        }
        return response() -> json($response);
    }

}
