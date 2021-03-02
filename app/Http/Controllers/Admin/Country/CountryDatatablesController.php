<?php
namespace App\Http\Controllers\Admin\Country;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\Country\Country;
use Carbon\Carbon;

class CountryDatatablesController extends Controller

{

    /**
     * Process ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getData(Request $request)
    {
    	return Datatables::make(Country::query())
    	->escapeColumns(['id'])
    	->editColumn('created_at', function ($country) {
    		return Carbon::parse($country->created_at)->format('d/m/Y H:i:s');
    	})->addColumn('actions', function ($country) {
    		return '<form action="'.route('country.destroy',$country->id).'" method="POST"><a href="'.route('country.edit',$country->id).'" class="btn btn-info">Edit</a>'.'  '.'<input type="hidden" value="'.csrf_token().'" name="_token"><input type="hidden" value="DELETE" name="_method"><button type="submit" class="btn btn-danger" data-toggle="confirmation">Delete</button></form>';
        })->make(true);
    }

}
?>