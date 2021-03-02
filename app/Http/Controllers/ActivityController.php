<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\User;
use Auth;

class ActivityController extends Controller
{
    /**
     * @param $username
     * @return RedirectResponse|View
     */
	public function index($username)
	{
		$slug=str_replace('-',' ',$username);
		$user=User::where('username',$slug)->first();
		if(Auth::user() && Auth::id()==$user->id || is_friend($user->id)){
			return view('profile.user-activity-all',compact('user'));
		}else{
			return redirect()->back()->with('error', 'You can see only your friends Activity');
		}
	}
}
