<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\UserNetworkController;
use App\Models\ForumCategory\ForumCategory;
use App\Models\Forums\Forums;
use App\Models\Comment\Comment;
use App\Models\UserFeed\UserFeed;
use Auth;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ForumController extends Controller
{
    /**
     * Show the topic Form.
     *
     */
    public function index()
    {
        if (Auth ::user() -> status == 1) {
            $forumRecentTopics = Forums ::orderBy('id', 'desc') -> take(10) -> get();
            $forum_category = ForumCategory ::where('status', '1') -> get();
            return view('forum.forum', compact('forum_category', 'forumRecentTopics'));
        } else {
            return redirect() -> back() -> with('error', 'You have not Permission to Add Forum Topics');
        }
    }

    /**
     * Insert the topic Form.
     *
     */
    public function addTopic(Request $request, UserNetworkController $network)
    {
        $validatedData = $request -> validate([
            'forum_category' => 'required',
            'topic_subject' => 'required',
            'topic' => 'required',
        ], [
            'forum_category.required' => 'Please Select Forum Category',
            'topic.required' => 'Please Enter Forum Topic',
            'topic_subject.required' => 'Please Enter Topic Subject'
        ]);

        $input = $request -> all();
        $user = Auth ::user();

        $forum = Forums ::create([
            'category_id' => $input['forum_category'],
            'user_id' => $user -> id,
            'topic' => $input['topic'],
            'topic_subject' => $input['topic_subject'],
            'status' => '0',
        ]);
        activity()
            -> causedBy(Auth ::user())
            -> withProperties(['topic' => $input['topic']])
            -> performedOn($forum)
            -> log('Add new Forum Topic');

        $input['properties'] = json_encode(['forum_topic' => $forum]);
        $input['user_id'] = Auth ::id();
        $feed = UserFeed ::create($input);

        $message = ' Create New Forum Topic';
        if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
            $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
        }
        if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
            $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
        }


        return redirect('forum') -> with('success', 'Forum Topic has been added successfully');
    }

    /**
     * Show List Forum.
     *
     */
    public function showList(Request $request)
    {
        if ($request -> get('search')) {
            $forumRecentTopics = Forums ::leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)->orderBy('forums.id', 'desc') -> take(10) -> get(array('forums.*'));
            $inputSearch = $request -> get('search');
            $forum_topics = Forums ::leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)->where('forums.topic', 'LIKE', '%' . $inputSearch . '%')-> select(['forums.*']) -> paginate(10);
            // $searchResults = Forums::where('topic','LIKE','%'.$inputSearch.'%')->get();
            // if(count($searchResults) > 0)
            // {
            //$countcomment = Comment::where('')
            //}
            // else
            // {
            // 	$forum_topics = array();
            // }
        } else {
            $forumRecentTopics = Forums ::leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)->orderBy('forums.id', 'desc') -> take(10) -> get(array('forums.*'));
            $forum_topics = Forums ::leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)->orderBy('forums.id', 'desc')-> select(['forums.*']) -> paginate(10);
        }
        return view('forum.forum_listing', compact('forum_topics', 'forumRecentTopics'));
    }

    /**
     * @param $category_id
     * @return Factory|View
     */
    public function formCategory($category_id)
    {
        $category = ForumCategory ::find($category_id);
        $forumRecentTopics = Forums ::orderBy('id', 'desc') -> take(10) -> get();
        return view('forum.category-topic', compact('category', 'forumRecentTopics'));
    }

    /**
     * @param $id
     * @return View
     */
    public function topic($id)
    {
        $forumRecentTopics = Forums ::leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)->orderBy('forums.id', 'desc') -> take(10) -> get(array('forums.*'));
        $topicDetails = Forums ::leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)->where('forums.id',$id)->first(array('forums.*'));
        $comments = Comment ::leftjoin('users as u','u.id','=','comments.user_id')-> where('u.deleted_at',null)->where('topic_id', $id) -> get(array('comments.*'));
        if (isset($topicDetails)) {
            return view('forum.forum-details', compact('topicDetails', 'comments', 'forumRecentTopics'));
        } else {
            return redirect() -> route('forumlist') -> with('error', 'This topic no longer available');
        }
    }

    /**
     * @param Request $request
     * @param \App\Http\Controllers\UserNetworkController $network
     * @return RedirectResponse
     */
    public function addComment(Request $request, UserNetworkController $network)
    {
        $validatedData = $request -> validate([
            'comment' => 'required',
        ], [
            'comment.required' => 'Please Enter a Comment',
        ]);
        if (Auth ::user() -> status == 1) {
            $input = $request -> all();
            $user = Auth ::user();
            $comment = Comment ::create([
                'topic_id' => $input['topic_id'],
                'user_id' => $user -> id,
                'comment' => $input['comment'],
            ]);
            activity()
                -> causedBy(Auth ::user())
                -> withProperties(['topic' => $comment -> forumtopic -> topic, 'topic_id' => $input['topic_id']])
                -> performedOn($comment)
                -> log('left a comment');

            $input['properties'] = json_encode(['comment' => $comment]);
            $input['user_id'] = Auth ::id();
            $feed = UserFeed ::create($input);

            $message = ' left a comment on Forum Topic';
            if (isset(Auth ::user() -> friendsOfMineNetwork) && count(Auth ::user() -> friendsOfMineNetwork)) {
                $network -> sendNetworkNotification(Auth ::user() -> friendsOfMineNetwork, $message);
            }
            if (isset(Auth ::user() -> friendOfNetwork) && count(Auth ::user() -> friendOfNetwork)) {
                $network -> sendNetworkNotification(Auth ::user() -> friendOfNetwork, $message);
            }

            return redirect() -> back() -> with('success', 'Your Comment has been added Successfully!!');
        } else {
            return redirect() -> back() -> with('error', 'You have not Permission to Add Comment on Forum Topics');
        }
    }

}
