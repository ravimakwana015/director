<?php

namespace App\Http\Controllers\Admin\Report;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Like\Like;
use App\User;
use Carbon\Carbon;

class ReportTableController extends Controller
{
	/**
	 * getForDataTable
	 *
	 * @param  mixed $input
	 *
	 * @return void
	 */
	public function getForDataTable($input)
	{
		$dataTableQuery = User::select(['*']);
		if($input['month']!=''){
			$dataTableQuery->whereMonth('created_at', '=', $input['month']);
		}

		if($input['year']!=''){
			$dataTableQuery->whereYear('created_at', '=', $input['year']);
		}

		if($input['from_date']!='' && $input['to_date']!='')
		{
			$dataTableQuery->whereBetween('created_at', array($input['from_date'], $input['to_date']));
		}

		if($input['status']!=''){
			$dataTableQuery->where('status', '=', $input['status']);
		}
		return $dataTableQuery;
	}

    /**
     * Process ajax request.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */

     public function getData(Request $request)
     {
     	return Datatables::make($this->getForDataTable($request->all()))
     	->escapeColumns(['id'])
        ->addColumn('username', function ($userReports) {
            return '<a href="'.route('users.show',$userReports->id).'">'.$userReports->username.'</a>';
        })
     	->addColumn('created_at', function ($userReports) {
     		return Carbon::parse($userReports->created_at)->format('d/m/Y H:i:s');
     	})->addColumn('updated_at', function ($userReports) {
            return Carbon::parse($userReports->updated_at)->format('d/m/Y H:i:s');
        })->addColumn('status', function ($user) {
            if($user->status==1){
                return "<label class='label label-success'>Active</label>";
            }else{
                return "<label class='label label-warning'>Inactive</label>";
            }
        })->addColumn('icon', function ($user) {
            if(isset($user->profile_picture) && !empty($user->profile_picture))
            {
                return "<img src='".url('/')."/public/img/profile_picture/".$user->profile_picture."' width='50' height='50'>";
            }else{
                return "<img src='".url('/')."/public/front/images/196.jpg' alt='Profile Picture' width='50' height='50'>";
            }
        })
        ->addColumn('name', function ($user) {
            return $user->first_name.' '.$user->last_name;
        })->addColumn('likecount', function ($user) {
            Like::selectRaw('likes.*,count(profile_id) as userlikes')
            ->orderby('userlikes','DESC')
            ->groupBy('profile_id')
            ->get();

        })
        ->make(true);
    }
    // public function __invoke()
    // {
    //     return Datatables::make(User::query())
    //     ->addColumn('status', function ($user) {
    //         if($user->status==1){
    //             return "<label class='label label-success'>Active</label>";
    //         }else if($user->status==2)
    //         {
    //             return "<label class='label label-info'>Draft</label>";
    //         }
    //         else{
    //             return "<label class='label label-warning'>Inactive</label>";
    //         }
    //     })->addColumn('actions', function($user)
    //     {
    //         return '<form action="'.route('users.destroy',$user->id).'" method="POST"><a href="'.route('users.show',$user->id).'" class="btn btn-primary">Edit</a> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
    //     })
    //     ->rawColumns(['status','actions'])
    //     ->make(true);
    // }
}
