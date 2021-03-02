<?php

namespace App\Http\Controllers\Admin\ReferList;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invites\Invites;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ReferlistController extends Controller
{
    public function __invoke()
	{
		return Datatables::make($this->getForDataTable())
		->addColumn('created_at', function ($invites) {
            return Carbon::parse($invites->created_at)->format('d/m/Y H:i:s');
        }) ->addColumn('updated_at', function ($invites) {
            return Carbon::parse($invites->updated_at)->format('d/m/Y H:i:s');
        })->addColumn('username', function($invites)
		{
			return '<a href="'.route('users.show',$invites->referuser->id).'">'.$invites->referuser->first_name.' '.$invites->referuser->last_name.'</a>';
		})
		->addColumn('name', function($invites)
		{
			return $invites->name;
		})->addColumn('email', function($invites)
		{
			return $invites->email;
		})->addColumn('token', function($invites)
		{
			return $invites->token;
		})
		->rawColumns(['status','code','username','actions'])
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
        return Invites::query()->leftjoin('users as u','u.id','=','invites.user_id')-> where('u.deleted_at',null)
            -> select(['invites.*']);
    }
}
