<?php

namespace App\Http\Controllers\Admin\Sliders;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Sliders\SlidersRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class SlidersTableController.
 */
class SlidersTableController extends Controller
{
    /**
     * @var \App\Repositories\Admin\Sliders\SlidersRepository
     */
    protected $sliders;

    /**
     * @param \App\Repositories\Admin\Sliders\SlidersRepository $sliders
     */
    public function __construct(SlidersRepository $sliders)
    {
    	$this->sliders = $sliders;
    }

    /**
     *
     * @return mixed
     */
    public function __invoke()
    {
    	return Datatables::make($this->sliders->getForDataTable())
    	->addColumn('created_at', function ($sliders) {
    		return Carbon::parse($sliders->created_at)->format('d/m/Y H:i:s');
    	}) ->addColumn('updated_at', function ($sliders) {
        return Carbon::parse($sliders->updated_at)->format('d/m/Y H:i:s');
      })->addColumn('image', function ($sliders) {
            $img=asset('/public/img/sliders/'.$sliders->image); 
            return "<img src=".$img." width='80' height='80' />";
        })
      // ->addColumn('status', function ($sliders) {
      //       if($sliders->status==1){
      //           return "<label class='label label-success'>Active</label>";
      //       }else{
      //           return "<label class='label label-warning'>Inactive</label>";
      //       }
      //   })
      ->addColumn('actions', function ($sliders) {
          return '<form action="'.route('sliders.destroy',$sliders->id).'" method="POST"><a href="'.route('sliders.edit',$sliders->id).'" class="btn btn-primary">Edit</a>'.'  '.'<input type="hidden" value="'.csrf_token().'" name="_token"><input type="hidden" value="DELETE" name="_method"><button type="submit" class="btn btn-danger" data-toggle="confirmation">Delete</button></form>';
      })
        ->rawColumns(['status','actions','image'])
        ->make(true);
    }	
}
