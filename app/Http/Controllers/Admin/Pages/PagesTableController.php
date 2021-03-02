<?php

namespace App\Http\Controllers\Admin\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Pages\Page;
use Carbon\Carbon;

class PagesTableController extends Controller
{
    public function __invoke()
    {       
        return Datatables::make(Page::query())
        ->addColumn('created_at', function ($page) {
            return Carbon::parse($page->created_at)->format('d/m/Y H:i:s');
        }) ->addColumn('updated_at', function ($page) {
            return Carbon::parse($page->updated_at)->format('d/m/Y H:i:s');
        })
        ->addColumn('status', function ($page) {
            if($page->status==1){
                return "<label class='label label-success'>Active</label>";
            }else if($page->status==2)
            {
                return "<label class='label label-info'>Draft</label>";
            }
            else{
                return "<label class='label label-warning'>Inactive</label>";
            }
        })->addColumn('actions', function($page)
        {
            return '<form action="'.route('pages.destroy',$page->id).'" method="POST"><a href="'.route('pages.edit',$page->id).'" class="btn btn-primary">Edit</a> <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="'.csrf_token().'" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
        })
        ->rawColumns(['status','actions'])
        ->make(true);
    }
}
