<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Models\Contact;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ContactHelpController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('admin.contactUs.index');
    }

    public function getContactData()
    {
        return Datatables ::make(Contact ::query())
            -> editColumn('created_at', function ($page) {
                return Carbon ::parse($page -> created_at) -> format('d/m/Y H:i:s');
            }) -> editColumn('updated_at', function ($page) {
                return Carbon ::parse($page -> updated_at) -> format('d/m/Y H:i:s');
            }) -> editColumn('email', function ($page) {
                return '<a href="mailto:'.$page->email.'">'.$page->email.'</a>';
            }) -> editColumn('contact_number', function ($page) {
                return '<a href="tel:'.$page->contact_number.'">'.$page->contact_number.'</a>';
            })
             ->rawColumns(['email','contact_number'])
            -> make(true);
    }


}
