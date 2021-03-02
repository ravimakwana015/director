<?php

namespace App\Http\Controllers\Admin\ForumCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ForumCategory\ForumCategory;
use Carbon\Carbon;

class ForumCategoryTableController extends Controller
{
    public function __invoke()
    {       

        return Datatables::make(ForumCategory::query())
        ->addColumn('created_at', function ($forumcategory) {
            return Carbon::parse($forumcategory->created_at)->format('d/m/Y H:i:s');
        }) ->addColumn('updated_at', function ($forumcategory) {
            return Carbon::parse($forumcategory->updated_at)->format('d/m/Y H:i:s');
        })->addColumn('status', function ($forumcategory) {
            if($forumcategory->status==1){
                return "<label class='label label-success'>Active</label>";
            }else{
                return "<label class='label label-warning'>Inactive</label>";
            }
        })->addColumn('actions', function($forumcategory)
        {
            return '<form action="'.route('forumcategory.destroy',$forumcategory->id).'" method="POST"><a href="'.route('forumcategory.edit',$forumcategory->id).'" class="btn btn-primary">Edit</a> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
        })
        ->rawColumns(['status','actions'])
        ->make(true);
    }
}
