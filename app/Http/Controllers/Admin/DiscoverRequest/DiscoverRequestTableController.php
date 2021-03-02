<?php

namespace App\Http\Controllers\Admin\DiscoverRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DiscoversRequests\DiscoversRequests;
use Carbon\Carbon;

class DiscoverRequestTableController extends Controller
{
    public function __invoke()
    {

        return Datatables ::make($this -> getForDataTable())
            -> addColumn('created_at', function ($discoversrequest) {
                return Carbon ::parse($discoversrequest -> created_at) -> format('d/m/Y H:i:s');
            }) -> addColumn('updated_at', function ($discoversrequest) {
                return Carbon ::parse($discoversrequest -> updated_at) -> format('d/m/Y H:i:s');
            })
            -> addColumn('discover', function ($discoversrequest) {
                return $discoversrequest -> discovers -> title;
            })
            -> addColumn('competitions', function ($discoversrequest) {
                return $discoversrequest -> discovers -> competitions;
            })
            -> addColumn('profile_name', function ($discoversrequest) {
                return '<a href="' . route('users.show', $discoversrequest -> usersid -> id) . '">' . $discoversrequest -> usersid -> first_name . ' ' . $discoversrequest -> usersid -> last_name . '</a>';
                //return $topic->username->first_name;
            })
            -> addColumn('cv', function ($discoversrequest) {
                return '<a target="_blank" class="btn btn-info" href="' . url('/') . '/public/documents/discover_cv/' . $discoversrequest -> cv . '">Attachment</a>';
            })
            -> addColumn('actions', function ($discoversrequest) {
                return '<a class="btn btn-info" href="' . route('discoverrequestdetails.get', ['id' => $discoversrequest -> id]) . '">View</a>';
            })
            -> rawColumns(['status', 'cv', 'profile_name', 'actions'])
            -> make(true);
        // <a href="'.route('discoversrequest.edit',$discoversrequest->id).'" class="btn btn-primary">Edit</a>
    }

    /*
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the Career getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        return DiscoversRequests ::query() -> leftjoin('users as u', 'u.id', '=', 'discovers_requests.user_id') -> where('u.deleted_at', null)
            -> select(['discovers_requests.*']);
    }

    public function discoverRequestDetails($id)
    {
        $discoverdetail = DiscoversRequests ::find($id);
        return view('admin.discoverrequest.discoverrequestview', compact('discoverdetail'));
    }
}
