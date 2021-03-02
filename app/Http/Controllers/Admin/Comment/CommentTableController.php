<?php

namespace App\Http\Controllers\Admin\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Comment\Comment;
use Carbon\Carbon;

class CommentTableController extends Controller
{
	public function __invoke()
	{
		return Datatables::make($this->getForDataTable())
		->addColumn('created_at', function ($comment) {
			return Carbon::parse($comment->created_at)->format('d/m/Y H:i:s');
		}) ->addColumn('updated_at', function ($comment) {
			return Carbon::parse($comment->updated_at)->format('d/m/Y H:i:s');
		})
		->addColumn('Topic', function($comment)
		{
			return $comment->forumtopic->topic;
		})->addColumn('username', function($comment)
		{
			return '<a href="'.route('users.show',$comment->usercomment->id).'">'.$comment->usercomment->first_name.' '.$comment->usercomment->last_name.'</a>';
		})->addColumn('Comment Status', function ($comment) {
			if($comment->comment_status==1){
				return "<label class='label label-success'>Approve</label>";
			}else{
				return "<label class='label label-warning'>UnApprove</label>";
			}
		})->addColumn('actions', function($comment)
		{
			if($comment->comment_status==1){
				return '<form action="'.route('comment.destroy',$comment->id).'" method="POST"> <a href="'.route('comment.approve', [$comment->id,$comment->forumtopic->id, $comment->usercomment->id]).'" class="btn btn-warning">UnApprove</a> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
			}else{
				return '<form action="'.route('comment.destroy',$comment->id).'" method="POST"> <a href="'.route('comment.unapprove', [$comment->id,$comment->forumtopic->id, $comment->usercomment->id]).'" class="btn btn-success">Approve</a> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
			}
		})
		->rawColumns(['Comment Status','actions','username'])
		->make(true);
	}

	public function unapprove($id,$topic_id,$user_id)
	{
		Comment::where('id',$id)->where('topic_id',$topic_id)->where('user_id',$user_id)->update(['comment_status' => "1"]);
		return redirect()->route('comment.index');
	}

	public function approve($id,$topic_id,$user_id)
	{
		Comment::where('id',$id)->where('topic_id',$topic_id)->where('user_id',$user_id)->update(['comment_status' => "0"]);
		return redirect()->route('comment.index');
	}

	/**
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the Career getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        return Comment::query()->leftjoin('users as u','u.id','=','comments.user_id')-> where('u.deleted_at',null)
            -> select(['comments.*']);
    }

}
