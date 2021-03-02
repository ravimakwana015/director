<?php

namespace App\Http\Controllers\Admin\UserInvitationLink;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserInvitationLink\UserInvitationLinkRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class UserInvitationLinkTableController.
 */
class UserInvitationLinkTableController extends Controller
{
    /**
     * @var \App\Repositories\Admin\UserInvitationLink\UserInvitationLinkRepository
     */
    protected $UserInvitationLink;

    /**
     * @param \App\Repositories\Admin\UserInvitationLink\UserInvitationLinkRepository $UserInvitationLink
     */
    public function __construct(UserInvitationLinkRepository $UserInvitationLink)
    {
    	$this->UserInvitationLink = $UserInvitationLink;
    }

    /**
     *
     * @return mixed
     */
    public function __invoke()
    {
    	return Datatables::make($this->UserInvitationLink->getForDataTable())
    	->addColumn('created_at', function ($UserInvitationLink) {
    		return Carbon::parse($UserInvitationLink->created_at)->format('d/m/Y H:i:s');
    	})->addColumn('status', function ($UserInvitationLink) {
    		if($UserInvitationLink->status==1){
    			return "<label class='label label-success'>Registered</label>";
    		}else{
    			return "<label class='label label-warning'>Not Registered</label>";
    		}
    	})->addColumn('actions', function ($UserInvitationLink) {
    		return '<form action="'.route('user-invitation.destroy',$UserInvitationLink->id).'" method="POST"><a href="'.route('user-invitation.edit',$UserInvitationLink->id).'" class="btn btn-primary">Edit</a>'.'  '.'<input type="hidden" value="'.csrf_token().'" name="_token"><input type="hidden" value="DELETE" name="_method"><button type="submit" class="btn btn-danger" data-toggle="confirmation">Delete</button></form>';
    	})
    	->rawColumns(['status','actions'])
    	->make(true);
    }	
  }
