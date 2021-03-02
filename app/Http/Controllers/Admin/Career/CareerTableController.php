<?php

namespace App\Http\Controllers\Admin\Career;

use App\Http\Requests\Admin\Career\ManageCareerRequest;
use App\Repositories\Admin\Career\CareerRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CareerTableController.
 */
class CareerTableController extends Controller
{
    /**
     * @var CareerRepository
     */
    protected $career;

    /**
     * @param CareerRepository $career
     */
    public function __construct(CareerRepository $career)
    {
    	$this->career = $career;
    }

    /**
     * @param ManageCareerRequest $request
     *
     * @return mixed
     * @throws Exception
     */
    public function __invoke(ManageCareerRequest $request)
    {
    	return Datatables::make($this->career->getForDataTable())
    	->editColumn('created_at', function ($career) {
    		return Carbon::parse($career->created_at)->format('d/m/Y H:i:s');
    	})->editColumn('updated_at', function ($career) {
            return Carbon::parse($career->updated_at)->format('d/m/Y H:i:s');
        })->addColumn('status', function ($career) {
            if($career->status==1){
                return "<label class='label label-success'>Active</label>";
            }else{
                return "<label class='label label-warning'>Inactive</label>";
            }
        })->addColumn('actions', function ($career) {
    		return '<form action="'.route('careers.destroy',$career->id).'" method="POST"><a href="'.route('careers.edit',$career->id).'" class="btn btn-primary">Edit</a>'.'  '.'<input type="hidden" value="'.csrf_token().'" name="_token"><input type="hidden" value="DELETE" name="_method"><button type="submit" class="btn btn-danger" data-toggle="confirmation">Delete</button></form>';
    	})
    	->rawColumns(['status','actions'])
    	->make(true);
    }
}
