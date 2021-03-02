<?php

namespace App\Http\Controllers\Admin\CareersRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CareerRequest\CareerRequest;
use Carbon\Carbon;

class CareersRequestTableController extends Controller
{

	public function __invoke()
	{
		return Datatables::make($this->getForDataTable())
		->editColumn('created_at', function ($careerRequest) {
			return Carbon::parse($careerRequest->created_at)->format('d/m/Y H:i:s');
		})
		->editColumn('updated_at', function ($careerRequest) {
			return Carbon::parse($careerRequest->updated_at)->format('d/m/Y H:i:s');
		})
		->addColumn('career', function($careerRequest){
			return $careerRequest->career->title;
		})
		->addColumn('profile_name', function($careerRequest)
		{
			return '<a href="'.route('users.show',$careerRequest->careeruser->id).'">'.$careerRequest->careeruser->first_name.' '.$careerRequest->careeruser->last_name.'</a>';
			//return $topic->username->first_name;
		})
		->addColumn('cv', function($careerRequest)
		{
			return '<a target="_blank" class="btn btn-info" href="'.url('/').'/public/documents/career_cv/'.$careerRequest->cv.'">'.$careerRequest->cv.'</a>';
		})
		->addColumn('actions', function($careerRequest)
		{
			return '<a class="btn btn-info" href="'.route('careersrequestdetails.get', ['id'=>$careerRequest->id]).'">View</a>';
		})
		->rawColumns(['status','cv','profile_name','actions'])
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
        return CareerRequest::query()->leftjoin('users as u','u.id','=','career_requests.user_id')-> where('u.deleted_at',null)
            -> select(['career_requests.*']);
    }

	public function careersRequestDetails($id)
	{
		$careerdetail = CareerRequest::find($id);
		return view('admin.careersrequest.requestview',compact('careerdetail'));
	}
}
