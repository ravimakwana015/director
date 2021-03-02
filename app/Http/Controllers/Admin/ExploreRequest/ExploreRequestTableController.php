<?php

namespace App\Http\Controllers\Admin\ExploreRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ExploreRequest\ExploreRequest;
use Carbon\Carbon;

class ExploreRequestTableController extends Controller
{
	public function __invoke()
	{

		return Datatables::make($this->getForDataTable())
		->addColumn('created_at', function ($explorerequest) {
			return Carbon::parse($explorerequest->created_at)->format('d/m/Y H:i:s');
		})->addColumn('updated_at', function ($explorerequest) {
			return Carbon::parse($explorerequest->updated_at)->format('d/m/Y H:i:s');
		})
		->addColumn('explore', function($explorerequest){
			return $explorerequest->explore->title;
		})
		->addColumn('profile_name', function($explorerequest)
		{
			if(isset($explorerequest->exploreuser->id)){
				return '<a href="'.route('users.show',$explorerequest->exploreuser->id).'">'.$explorerequest->exploreuser->first_name.' '.$explorerequest->exploreuser->last_name.'</a>';
			}
		})
		->addColumn('cv', function($explorerequest)
		{
			return '<a target="_blank" class="btn btn-info" href="'.url('/').'/public/documents/explore_cv/'.$explorerequest->cv.'">CV</a>';
		})
		->addColumn('actions', function($explorerequest)
		{
			return '<a class="btn btn-info" href="'.route('explorerequestdetails.get', ['id'=>$explorerequest->id]).'">View</a>';
		})
		// ->addColumn('actions', function($explorerequest)
		// {
		// 	// return '<form action="'.route('careersrequest.destroy',$explorerequest->id).'" method="POST"><input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" class="btn btn-danger">Delete</button></form>';
		// })
		->rawColumns(['status','cv','profile_name','actions'])
		->make(true);
		// <a href="'.route('careersrequest.edit',$explorerequest->id).'" class="btn btn-primary">Edit</a>
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
        return ExploreRequest::query()->leftjoin('users as u','u.id','=','explore_requests.user_id')-> where('u.deleted_at',null)
            -> select(['explore_requests.*']);
    }

    /**
     * @param $id
     * @return View
     */
    public function ExploreRequestDetails($id)
	{
		$careerdetail = ExploreRequest::find($id);
		return view('admin.explorerequest.requestview',compact('careerdetail'));
	}
}
