<?php

namespace App\Http\Controllers\Admin\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\ManageUserRequest;
use App\Repositories\Admin\User\UserRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transactions\Transactions;

/**
 * Class UserTableController.
 */
class UserTableController extends Controller
{
    /**
     * @var \App\Repositories\Admin\User\UserRepository
     */
    protected $users;

    /**
     * @param \App\Repositories\Admin\User\UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ManageUserRequest $request)
    {
        return Datatables::make($this->users->getForDataTable())
        ->editColumn('created_at', function ($user) {
            return Carbon::parse($user->created_at)->format('d/m/Y H:i:s');
        })
        ->editColumn('updated_at', function ($user) {
            return Carbon::parse($user->updated_at)->format('d/m/Y H:i:s');
        })
        ->editColumn('username', function ($user) {
            return '<a href="' . route('users.show', $user->id) . '">' . $user->username . '</a>';
        })
        ->editColumn('status', function ($user) {
            if ($user->status == 1) {
                return "<label class='label label-success'>Active</label>";
            } else {
                return "<label class='label label-warning'>Inactive</label>";
            }
        })
        ->editColumn('user_type', function ($user) {
            if ($user->user_type == '1') {
                return 'Actor';
            } elseif ($user->user_type == '2') {
                return 'Model';
            } elseif ($user->user_type == '3') {
                return 'Musician';
            } elseif ($user->user_type == '4') {
                return 'Creator';
            }
        })
        ->addColumn('icon', function ($user) {
            if (isset($user->profile_picture) && !empty($user->profile_picture)) {
                return "<img src='" . url('/') . "/public/img/profile_picture/" . $user->profile_picture . "' width='50' height='50'>";
            } else {
                return "<img src='" . url('/') . "/public/front/images/196.jpg' alt='Profile Picture' width='50' height='50'>";
            }
        })
        ->addColumn('name', function ($user) {
            return $user->first_name . ' ' . $user->last_name;
        })
        ->addColumn('access_code', function ($user) {
            if($user->usertransactions['amount'] > 0 || $user->usertransactions['coupon']==1)
            {
                return $user->access_code;
            }
        })
        ->addColumn('actions', function ($user) {
            return '<form action="' . route('users.destroy', $user->id) . '" method="POST">
            <a href="' . route('users.show', $user->id) . '" class="btn btn-primary">View</a>
            <a href="' . route('users.edit', $user->id) . '" class="btn btn-primary">Edit</a>
            ' . '  ' . '<input type="hidden" value="' . csrf_token() . '" name="_token">
            <input type="hidden" value="DELETE" name="_method">
            <button type="button" class="btn btn-danger" onclick="deleteUserModal('.$user->id.')">Delete</button>
            </form>';
        })
        ->rawColumns(['status', 'actions', 'icon', 'username'])
        ->make(true);
    }
}
