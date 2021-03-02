<?php

namespace App\Http\Controllers\Admin\Auth\User;


use App\Http\Requests\Admin\User\ManageUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use App\Repositories\Admin\User\UserRepository;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Responses\Admin\User\CreateResponse;
use App\Models\Forums\Forums;
use App\Models\CareerRequest\CareerRequest;
use App\Models\DiscoversRequests\DiscoversRequests;
use App\Models\ExploreRequest\ExploreRequest;
use App\Models\Transactions\Transactions;
use App\Models\Invites\Invites;
use App\Models\UserFeed\UserFeed;
use App\User;
use DB;
use Hash;
use Stripe;


class UserController extends Controller
{

    protected $user;

    /**
     * @param \App\Repositories\Admin\user\UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this -> user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.users.index');
    }


    /**
     * @return CreateResponse
     */
    public function create()
    {
        return new CreateResponse();
    }


    /**
     * @param StoreUserRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $this -> user -> create($request -> except('_token', 'confirm_password'));
        return redirect() -> route('users.index')
            -> with('success', 'User Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     * @throws Stripe\Exception\ApiErrorException
     */
    public function show($id)
    {
        Stripe\Stripe ::setApiKey(config('services.stripe.secret'));
        $user = User ::find($id);
        $likeCount = likeCount($id);
        $forum = Forums ::orderBy('id', 'desc') -> where('user_id', $user -> id) -> get();
        $career = CareerRequest ::orderBy('id', 'desc') -> where('user_id', $user -> id) -> get();
        $discoversRequests = DiscoversRequests ::orderBy('id', 'desc') -> where('user_id', $user -> id) -> get();
        $explorerequest = ExploreRequest ::orderBy('id', 'desc') -> where('user_id', $user -> id) -> get();
        $transcation = Transactions ::where('payment_status', 1) -> where('user_id', $user -> id) -> get();
        $invite = Invites ::where('user_id', $user -> id) -> get();
        // $friends  = UserNetwork::where('user_id',$user->id)->get();
        $feeds = UserFeed ::orderBy('id', 'desc') -> where('user_id', $user -> id) -> get();


        if (isset($user -> owner -> stripe_id) && $user -> owner -> stripe_id != $user -> username) {

            $plan = Stripe\Plan ::retrieve($user -> subscription('main') -> stripe_plan);

            $subscription = \Stripe\Subscription ::retrieve($user -> subscription('main') -> stripe_id);
            return view('admin.users.show', compact('user', 'likeCount', 'forum', 'career', 'discoversRequests', 'explorerequest', 'plan', 'subscription', 'transcation', 'invite', 'feeds'));
        } else {
            $customPlan = $user -> owner;
            return view('admin.users.show', compact('user', 'likeCount', 'forum', 'career', 'discoversRequests', 'explorerequest', 'customPlan', 'transcation', 'invite', 'feeds'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $users = User ::find($id);

        return view('admin.users.edit', compact('users'));
    }


    /**
     * @param \App\Http\Requests\Admin\User\UpdateUserRequest $request
     *
     */
    public function update(UpdateUserRequest $request, $id)
    {
        //$user = User ::find($id);
        $user = $request -> all();

       DB::table('users')
           ->where('id', $id)
           ->update(['username' => $user['username'],'first_name' => $user['first_name'],'last_name' => $user['last_name'],'email' => $user['email'],'status' => $user['status'],'user_type'=>$user['user_type'], 'private_user'=>$user['private_user'], 'verify'=>$user['verify']]);
        //$this -> user -> update($user, $request -> except('_token'),$request -> except('profile_picture'), $id);

        return redirect() -> route('users.index') -> with('success', 'User Updated Successfully');
    }

    /**
     * Delete User
     */
    public function destroy($id)
    {

        $user = User ::withTrashed() -> find($id);
        $this -> user -> delete($user);

        return redirect() -> route('user.deleted')
            -> with('success', 'User Deleted Successfully');
    }

    /**
     * Delete User
     */
    public function restore($id)
    {

        $user = User ::withTrashed() -> find($id) -> restore();

        return redirect() -> route('users.index')
            -> with('success', 'User Restored Successfully');
    }

    /**
     * Delete User
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteUser(Request $request)
    {
        $input = $request -> all();
        $user = User ::find($input['id']);
        $user -> delete();

        return response() -> json(['status' => 1], 200);
    }

    public function getDeleted()
    {
        $users = User ::onlyTrashed() -> paginate(10);
        return view('admin.users.deleted', compact('users'));
    }
}
