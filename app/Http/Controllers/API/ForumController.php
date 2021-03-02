<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Forums\Forums;
use App\Models\ForumCategory\ForumCategory;
use App\Models\Comment\Comment;
use App\Notifications\LikeNotification;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Validator;
use Auth;
use App\Http\Controllers\UserNetworkController;
use App\Models\UserFeed\UserFeed;


class ForumController extends ResponseController
{
	public function index(Request $request)
	{
		$input=$request->all();
		if(isset($input['search']) && $input['search']!=''){
			
			$forumRecentTopics = Forums::orderBy('id','desc')->take(10)->get();
			$inputSearch = $request->get('search');
			$forum_topics = Forums::where('topic','LIKE','%'.$inputSearch.'%')->paginate(5);

		}else{
			$forumRecentTopics = Forums::orderBy('id','desc')->take(10)->get();	
			$forum_topics = Forums::orderBy('id','desc')->get();
		}
		$data=[
			'forum_topics'      => $forum_topics,
			'forumRecentTopics' => $forumRecentTopics
		];
		return $this->sendResponse($data);
	}
	public function topic(Request $request)
	{
		$input=$request->all();
		$forumRecentTopics = Forums::orderBy('id','desc')->take(10)->get();
		$topicDetails = Forums::find($input['id']);
		$comments = Comment::where('topic_id',$input['id'])->get();
		if(isset($topicDetails)){
			$data=[
				'topicDetails'      => $topicDetails,
				'comments' 					=> $comments,
				'forumRecentTopics' => $forumRecentTopics
			];
			return $this->sendResponse($data);
		}else{
			return $this->sendError('Forum topic not Found');
		}
	}

	/**
    * Show the  Forum Category.
    *
    */
	public function forumCategory()
	{
		$forum_category = ForumCategory::where('status','1')->get();
		return $this->sendResponse($forum_category);
	}
	/**
    * Insert the topic Forum.
    *
    */
	public function addTopic(Request $request, UserNetworkController $network)
	{

		$validator = Validator::make($request->all(),[ 
			'forum_category' => 'required',
			'topic_subject'  => 'required',
			'topic'          => 'required',
		],[
			'forum_category.required' => 'Please Select Forum Category',
			'topic.required'          => 'Please Enter Forum Topic',
			'topic_subject.required'  => 'Please Enter Topic Subject',
		]);
		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input = $request->all();
		$user = Auth::user();

		$forum_category = ForumCategory::find($input['forum_category']);
		if(!isset($forum_category)){
			return $this->sendError('Forum Category not Found');
		}

		$forum = Forums::create([
			'category_id'  	=> $input['forum_category'],
			'user_id' 		=> $user->id,
			'topic' 		=> $input['topic'],
			'topic_subject' => $input['topic_subject'],
			'status' 		=> '0',
		]);
		activity()
		->causedBy(Auth::user())
		->withProperties(['topic' => $input['topic']])
		->performedOn($forum)
		->log('Add new Forum Topic');

		$input['properties'] = json_encode(['forum_topic' => $forum]);
		$input['user_id'] = Auth::id();
		$feed=UserFeed::create($input);

		$message=' Create New Forum Topic';
		if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
		{
			$network->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
		}
		if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
		{
			$network->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
		}
		return $this->sendSuccess('Forum Topic has been added Successfully !!');
	}

	public function addComment(Request $request, UserNetworkController $network)
	{
		$validator = Validator::make($request->all(),[
			'comment' => 'required',
			'topic_id' => 'required',
		],[
			'comment.required' => 'Please Enter a Comment',
			'topic_id.required' => 'Please Enter Topic Id',
		]);
		if ($validator->fails()) {
			return $this->sendError($validator->errors());
		}

		$input = $request->all();
		$user = Auth::user();
		$forum = Forums::find($input['topic_id']);
		if(!isset($forum)){
			return $this->sendError('Forum topic not Found');
		}
		$comment = Comment::create([
			'topic_id'  => $input['topic_id'],
			'user_id'  => $user->id,
			'comment' => $input['comment'],
		]);
		activity()
		->causedBy(Auth::user())
		->withProperties(['topic' => $comment->forumtopic->topic,'topic_id'  => $input['topic_id']])
		->performedOn($comment)
		->log('left a comment');

		$input['properties'] = json_encode(['comment' => $comment]);
		$input['user_id'] = Auth::id();
		$feed=UserFeed::create($input);

		$message=' Left a comment on Forum Topic';
		if(isset(Auth::user()->friendsOfMineNetwork) &&  count(Auth::user()->friendsOfMineNetwork))
		{
			$network->sendNetworkNotification(Auth::user()->friendsOfMineNetwork,$message);
		}
		if(isset(Auth::user()->friendOfNetwork) &&  count(Auth::user()->friendOfNetwork))
		{
			$network->sendNetworkNotification(Auth::user()->friendOfNetwork,$message);
		}
		return $this->sendSuccess('Your Comment has been added Successfully!!');
	}

}
