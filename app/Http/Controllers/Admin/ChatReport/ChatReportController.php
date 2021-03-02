<?php

namespace App\Http\Controllers\Admin\ChatReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Friend;
use Carbon\Carbon;

class ChatReportController extends Controller
{
	public function index(){
		return view('admin.chatreport.index');
	}
    public function chatReportData()
	{       
		return Datatables::make(Friend::where('is_report',1)->get())
		->addColumn('created_at', function ($chatreport) {
            return Carbon::parse($chatreport->created_at)->format('d/m/Y H:i:s');
        })->addColumn('updated_at', function ($chatreport) {
            return Carbon::parse($chatreport->updated_at)->format('d/m/Y H:i:s');
        })->addColumn('report_by', function ($chatreport) {

        	return '<a href="'.route('users.show',$chatreport->report_by).'">'.$chatreport->reportBy->first_name.' '.$chatreport->reportBy->last_name.'</a>';
        })->addColumn('report_to', function ($chatreport) {
        	return '<a href="'.route('users.show',$chatreport->friend_id).'">'.$chatreport->userFriend->first_name.' '.$chatreport->userFriend->last_name.'</a>';
            // return $chatreport->userFriend->first_name.' '.$chatreport->userFriend->last_name;
        })
		->rawColumns(['report_by','report_to'])
		->make(true);
	}
}
