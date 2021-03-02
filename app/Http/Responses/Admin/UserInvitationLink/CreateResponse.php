<?php

namespace App\Http\Responses\Admin\UserInvitationLink;

use Illuminate\Contracts\Support\Responsable;

class CreateResponse implements Responsable
{

    /**
     * In Response.
     *
     * @param \App\Http\Requests\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toResponse($request)
    {
        return view('admin.userinvitation.create');
    }
}
