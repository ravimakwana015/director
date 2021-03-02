<?php

namespace App\Http\Controllers\Admin\ContactInquire;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Contactinquire\Contactinquire;
use Carbon\Carbon;
use DB;

class ContactInquireTableController extends Controller
{

    /**
     * getForDataTable
     *
     * @return void
     */
    public function getForDataTable()
    {
        $dataTableQuery = Contactinquire ::withTrashed() -> leftJoin('users', 'contactinquires.user_id', '=', 'users.id')
            -> where('users.deleted_at', null)
            -> select([
                'contactinquires.*',
                'users.first_name',
                DB ::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"),
            ]);
        return $dataTableQuery;
    }

    public function __invoke()
    {

        return Datatables ::make($this -> getForDataTable())
            -> editColumn('created_at', function ($inquire) {
                return Carbon ::parse($inquire -> created_at) -> format('d/m/Y H:i:s');
            }) -> editColumn('updated_at', function ($inquire) {
                return Carbon ::parse($inquire -> updated_at) -> format('d/m/Y H:i:s');

            })
            -> addColumn('full_name', function ($inquire) {
                return '<a href="' . route('users.show', $inquire -> user_id) . '">' . $inquire -> full_name . '</a>';
            })
            -> addColumn('photo', function ($inquire) {
                if (isset($inquire -> photo) && !empty($inquire -> photo) && file_exists(public_path('img/inquiry/') . $inquire -> photo)) {
                    return "<img src='" . url('/') . "/public/img/inquiry/" . $inquire -> photo . "' width='50' height='50'>";
                } else {
                    return "<img src='" . url('/') . "/public/front/images/196.jpg' alt='Profile Picture' width='50' height='50'>";
                }
            })
            -> addColumn('actions', function ($inquire) {
                $action = '';
                if ($inquire -> deleted_at != null) {
                    $action = '<form action="' . route('contactinquire.destroy', $inquire -> id) . '" method="POST">  <input type="hidden" value="DELETE" name="_method"><input type="hidden" value="' . csrf_token() . '" name="_token"><button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button></form>';
                } else {
                    // $action = '<a href="'.route('contactinquire.edit',$inquire->id).'" class="btn btn-primary">View</a>';

                }
                return $action;
            })
            -> rawColumns(['photo', 'full_name', 'actions'])
            -> make(true);
    }
}
