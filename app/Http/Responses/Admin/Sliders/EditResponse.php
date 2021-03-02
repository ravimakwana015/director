<?php

namespace App\Http\Responses\Admin\User;

use Illuminate\Contracts\Support\Responsable;

class EditResponse implements Responsable
{
    protected $course;
    protected $authors;

    public function __construct($course,$authors)
    {
        $this->course = $course;
        $this->authors = $authors;
    }

    public function toResponse($request)
    {

        return view('admin.users.edit');
    }
}
