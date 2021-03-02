<?php

namespace App\Http\Controllers\Admin\Refercode;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Refercode\Refercode;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AdminReferTableCode extends Controller
{
    public function __invoke()
	{
		return Datatables::make(Refercode::query())
		->addColumn('created_at', function ($discovers) {
            return Carbon::parse($discovers->created_at)->format('d/m/Y H:i:s');
        }) ->addColumn('updated_at', function ($discovers) {
            return Carbon::parse($discovers->updated_at)->format('d/m/Y H:i:s');
        })
		->rawColumns(['status','actions'])
		->make(true);
	}
}
