<?php

namespace App\Http\Responses\Admin\User;

use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable
{

    public function toResponse($request)
    {
        return view('admin.users.index');
    }
}
