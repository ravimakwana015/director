<?php
namespace App\Http\Controllers\Admin\UserInvitationLink;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SendUserInviteLink\SendUserInviteLink;
use App\Repositories\Admin\UserInvitationLink\UserInvitationLinkRepository;
use App\Http\Requests\Admin\UserInvitationLink\StoreUserInvitationLinkRequest;
use App\Http\Requests\Admin\UserInvitationLink\UpdateUserInvitationLinkRequest;
use App\Http\Responses\Admin\UserInvitationLink\CreateResponse;
use App\Models\Country\Country;
use DB;
use Hash;


class UserInvitationLinkController extends Controller
{
    /**
     * @var UserInvitationLinkRepository
     */
    protected $userinvitationlink;

    /**
     * @param \App\Repositories\Admin\UserInvitationLink\UserInvitationLinkRepository $userinvitationlink
     */
    public function __construct(UserInvitationLinkRepository $userinvitationlink)
    {
        $this->userinvitationlink = $userinvitationlink;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.userinvitation.index');
    }


    /**
     * @param \App\Http\Requests\Admin\UserInvitationLink\ManageUserInvitationLinkRequest $request
     *
     * @return \App\Http\Responses\Admin\UserInvitationLink\CreateResponse
     */
    public function create()
    {
        return new CreateResponse();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @param \App\Http\Requests\Admin\UserInvitationLink\StoreUserInvitationLinkRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreUserInvitationLinkRequest $request)
    {
        $this->userinvitationlink->create($request->except('_token'));
        return redirect()->route('user-invitation.index')
        ->with('success','User Invitation Link Updated Successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userinvitationlink = UserInvitationLink::find($id);

        return view('admin.userinvitation.edit',compact('UserInvitationLink'));
    }


     /**
     * @param \App\Http\Requests\Admin\UserInvitationLink\UpdateUserInvitationLinkRequest $request
     *
     */
     public function update(UpdateUserInvitationLinkRequest $request, $id)
     {
         $userinvitationlink = UserInvitationLink::find($id);
         $this->userinvitationlink->update($userinvitationlink,$request->except('_token'),$id);

        return redirect()->route('user-invitation.index')->with('success','User Invitation Link Updated Successfully');
    }

    /**
     * Delete UserInvitationLink
     */
    public function destroy($id)
    {   
        $userinvitationlink = UserInvitationLink::find($id);
        $this->userinvitationlink->delete($userinvitationlink);

        return redirect()->route('user-invitation.index')
        ->with('success','User Invitation Link Deleted Successfully');
    }
}