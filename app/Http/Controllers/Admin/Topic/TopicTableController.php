<?php

namespace App\Http\Controllers\Admin\Topic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Forums\Forums;
use Carbon\Carbon;

class TopicTableController extends Controller
{
	public function __invoke()
	{
		return Datatables::make($this->getForDataTable())
		->editColumn('created_at', function ($topic) {
			return Carbon::parse($topic->created_at)->format('d/m/Y H:i:s');
		})
		->editColumn('updated_at', function ($topic) {
			return Carbon::parse($topic->updated_at)->format('d/m/Y H:i:s');
		})
		->addColumn('Category Name', function($topic)
		{
			return $topic->forumCategory->title;
		})
		->addColumn('username', function($topic)
		{
			return '<a href="'.route('users.show',$topic->username->id).'">'.$topic->username->first_name.' '.$topic->username->last_name.'</a>';
		})
		->addColumn('actions', function($topic)
		{
			return '<form action="'.route('topic.destroy',$topic->id).'" method="POST"> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
		})
		->rawColumns(['status','actions','username'])
		->make(true);
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
        return Forums::query()->leftjoin('users as u','u.id','=','forums.user_id')-> where('u.deleted_at',null)
            -> select(['forums.*']);
    }
}
