<?php

namespace App\Http\Controllers\Admin\AccessCode;

use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AccessCode\AccessCode;
use Carbon\Carbon;
use App\User;
use App\Models\Transactions\Transactions;
use DB;

class AccessCodeTableController extends Controller
{
    public function __invoke()
    {
        return Datatables::make(AccessCode::query())
        ->editColumn('created_at', function ($accessCode) {
            return Carbon::parse($accessCode->created_at)->format('d/m/Y H:i:s');
        }) ->editColumn('updated_at', function ($accessCode) {
            return Carbon::parse($accessCode->updated_at)->format('d/m/Y H:i:s');
        })
        ->addColumn('count', function ($accessCode) {
            $pointCount = DB::table('users')->where([
                ['access_code', '=', $accessCode->getcountcode['access_code']],
                ['access_code', '!=', NULL],
                ['status', '=', 1]
            ])->count();
            if($pointCount>0){
                 return '<a href="'.route('users.index').'">'.$pointCount.'</a>';
            }else{
                return $pointCount;
            }

        })
        ->addColumn('status', function ($accessCode) {
            if($accessCode->status==1){
                return "<label class='label label-success'>Active</label>";
            }else{
                return "<label class='label label-warning'>Inactive</label>";
            }
        })->addColumn('actions', function($accessCode)
        {
            return '<form action="'.route('access-code.destroy',$accessCode->id).'" method="POST">
            <a href="'.route('access-code.edit',$accessCode->id).'" class="btn btn-primary">Edit</a>
            <input type="hidden" value="DELETE" name="_method">
            <input type="hidden" value="'.csrf_token().'" name="_token">
            <button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
        })
        ->rawColumns(['status','actions','count'])
        ->make(true);
    }
}
