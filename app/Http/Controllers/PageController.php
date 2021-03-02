<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages\Page;

class PageController extends Controller
{

	public function index($slug)
    {
        $page=Page::where('status','1')->where('slug',$slug)->first();
        return view('pages.index',compact('page'));
    }

}
