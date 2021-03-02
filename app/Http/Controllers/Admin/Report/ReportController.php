<?php

namespace App\Http\Controllers\Admin\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
	public function userReports()
	{		
		$users = User::selectRaw('MONTH(users.created_at) AS month,YEAR(users.created_at) AS year ,MONTH(users.status) AS status')->get()->toarray();
	
		$months=[];
		$year=[];
		$status=[];
		foreach ($users as $key => $value) {
			$months[$value['month']]=$value['month'];
			$year[$value['year']]=$value['year'];
			$status[$value['status']]=$value['status'];
		}

		return view('admin.reports.index',compact('months','year','status'));
	}
}
