<?php

namespace App\Http\Controllers\Admin\Discover;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Discovers\Discovers;
use Carbon\Carbon;


class DiscoverTableController extends Controller
{
    public function __invoke()
    {
        return Datatables::make(Discovers::query())
            ->editColumn('created_at', function ($discovers) {
                return Carbon::parse($discovers->created_at)->format('d/m/Y H:i:s');
            })->editColumn('updated_at', function ($discovers) {
                return Carbon::parse($discovers->updated_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('description', function ($discovers) {
                return $discovers->description;
            })
            ->addColumn('status', function ($discovers) {
                if ($discovers->status == 1) {
                    return "<label class='label label-success'>Active</label>";
                } else if ($discovers->status == 2) {
                    return "<label class='label label-info'>Result Declared</label>";
                } else {
                    return "<label class='label label-warning'>Inactive</label>";
                }
            })->addColumn('actions', function ($discovers) {

                $button = '';
                if ($discovers->status == 1) {
                    $button .= '<form action="' . route('discover.destroy', $discovers->id) . '" method="POST">
                <a href="' . route('discover.edit', $discovers->id) . '" class="btn btn-primary">Edit</a>
                <input type="hidden" value="DELETE" name="_method">
                <input type="hidden" value="' . csrf_token() . '" name="_token">
                <button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button>
                <a href="javascript:;" class="btn btn-success" onclick="declareResult('.$discovers->id.')">Declare Result</a>
                </form>';
                }elseif ($discovers->status == 2) {
                    $button .= '<form action="' . route('discover.destroy', $discovers->id) . '" method="POST">
                <a href="' . route('discover.edit', $discovers->id) . '" class="btn btn-primary">Edit</a>
                <a href="javascript:;" class="btn btn-info" onclick="vieWinner('.$discovers->id.')" title="View winner"><i class="fa fa-trophy" aria-hidden="true"></i></a>
                <input type="hidden" value="DELETE" name="_method">
                <input type="hidden" value="' . csrf_token() . '" name="_token">
                <button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button>
                </form>';
                } else {
                    $button .= '<form action="' . route('discover.destroy', $discovers->id) . '" method="POST">
                <a href="' . route('discover.edit', $discovers->id) . '" class="btn btn-primary">Edit</a>
                <input type="hidden" value="DELETE" name="_method">
                <input type="hidden" value="' . csrf_token() . '" name="_token">
                <button type="submit" data-toggle="confirmation" class="btn btn-danger">Delete</button>
                </form>';
                }
                return $button;
            })
            ->rawColumns(['description', 'status', 'actions'])
            ->make(true);
    }
}
