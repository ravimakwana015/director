<?php

namespace App\Http\Controllers\Admin\Plans;

use App\Http\Controllers\Controller;
use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PlansTableController.
 */
class PlansTableController extends Controller
{


    /**
     *
     * @return mixed
     */
    public function __invoke()
    {
    	return Datatables::make(MembershipSubscriptionPlan::query())
    	->addColumn('created_at', function ($plans) {
    		return Carbon::parse($plans->created_at)->format('d/m/Y H:i:s');
    	})->addColumn('updated_at', function ($plans) {
            return Carbon::parse($plans->updated_at)->format('d/m/Y H:i:s');
        })->addColumn('status', function ($plans) {
    		if($plans->status==1){
    			return "<label class='label label-success'>Active</label>";
    		}else{
    			return "<label class='label label-warning'>Inactive</label>";
    		}
    	})->addColumn('actions', function ($plans) {
    		return '<form action="'.route('plans.destroy',$plans->id).'" method="POST"><a href="'.route('plans.edit',$plans->id).'" class="btn btn-primary">Edit</a>'.'  '.'<input type="hidden" value="'.csrf_token().'" name="_token"><input type="hidden" value="DELETE" name="_method"><button type="submit" class="btn btn-danger" data-toggle="confirmation">Delete</button></form>';
    	})
    	->rawColumns(['status','actions'])
    	->make(true);
    }	
  }
