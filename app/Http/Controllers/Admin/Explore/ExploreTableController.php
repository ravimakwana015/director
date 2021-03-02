<?php

namespace App\Http\Controllers\Admin\Explore;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Explore\Explore;
use Carbon\Carbon;

class ExploreTableController extends Controller
{
    public function __invoke()
	{       
		return Datatables::make(Explore::query())
		->addColumn('created_at', function ($explore) {
            return Carbon::parse($explore->created_at)->format('d/m/Y H:i:s');
        }) ->addColumn('updated_at', function ($explore) {
            return Carbon::parse($explore->updated_at)->format('d/m/Y H:i:s');
        })
		->addColumn('description', function ($explore) {
			return $explore->description;
		})
		->addColumn('status', function ($explore) {
			if($explore->status==1){
				return "<label class='label label-success'>Active</label>";
			}else{
				return "<label class='label label-warning'>Inactive</label>";
			}
		})->addColumn('actions', function($explore)
		{
			return '<form action="'.route('explore.destroy',$explore->id).'" method="POST"><a href="'.route('explore.edit',$explore->id).'" class="btn btn-primary">Edit</a> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
		})
		->rawColumns(['description','status','actions'])
		->make(true);
	}
}
