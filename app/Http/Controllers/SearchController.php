<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genres\Genres;
use App\Models\Like\Like;
use App\Models\Accents\Accents;
use App\User;
use Auth;
use DB;

class SearchController extends Controller
{
	public function searchList(Request $request)
	{	
		$userdata = User::groupBy('country')->whereNotNull('country')->pluck('country');
		$playing_age = User::groupBy('playing_age')->whereNotNull('playing_age')->pluck('playing_age');
		$heights = User::groupBy('height')->whereNotNull('height')->pluck('height');
		$eye_colours = User::groupBy('eye_colour')->whereNotNull('eye_colour')->pluck('eye_colour');
		$hair_colours = User::groupBy('hair_colour')->whereNotNull('hair_colour')->pluck('hair_colour');
		$instruments = User::groupBy('instrument')->whereNotNull('instrument')->pluck('instrument');
		$gender = User::groupBy('gender')->whereNotNull('gender')->pluck('gender');
		$genre = Genres::groupBy('genre')->whereNotNull('genre')->pluck('genre');
		$acents = Accents::groupBy('accent')->whereNotNull('accent')->pluck('accent');

		$input = $request->all();
		$search = $input['search'];

		

		$users = User::where('users.first_name','LIKE','%'.$search.'%')
		->orWhere('users.last_name', 'LIKE', '%'.$search.'%')
		->orWhere('users.email', 'LIKE', '%'.$search.'%')
		->orWhere('users.city', 'LIKE', '%'.$search.'%')
		->orWhere('users.user_type', 'LIKE', '%'.$search.'%')
		->orWhere('users.gender', $search)
		->orWhere('users.username', 'LIKE', '%'.$search.'%')
		->paginate(40);


		$ids=[];
		foreach ($users as $key => $user) {
			if(Auth::user() && $user->id==Auth::user()->id){
				unset($users[$key]);
			}else{
				$ids[]=$user->id;
			}
		}

		$likeusers=Like::whereIn('profile_id',$ids)
		->selectRaw('likes.*,count(profile_id) as userlikes')
		->groupBy('profile_id')
		->orderby('userlikes','DESC')
		->get();

		$searchResult=[];
		if(isset($likeusers) && count($likeusers)){
			$likesids=[];
			foreach ($likeusers as $key => $likeuser) 
			{
				$likesids[]=$likeuser->profile_id;
				$likeuser->profilelike->likes=$likeuser->userlikes;
				array_push($searchResult, $likeuser->profilelike);
			}
			foreach ($users as $key => $user) 
			{
				if(!in_array($user->id,$likesids)){
					array_push($searchResult, $user);
				}
			}
		}else{
			foreach ($users as $key => $user) 
			{
				array_push($searchResult, $user);
			}
		}
		
		$count = count($searchResult);
		
		return view('search-profile-filter',compact('input','searchResult','userdata','playing_age','gender','genre','count','users'));
	}

	public function advanceSearchList(Request $request)
	{
		$userdata = User::groupBy('country')->whereNotNull('country')->pluck('country');
		$playing_age = User::groupBy('playing_age')->whereNotNull('playing_age')->pluck('playing_age');
		$heights = User::groupBy('height')->whereNotNull('height')->pluck('height');
		$eye_colours = User::groupBy('eye_colour')->whereNotNull('eye_colour')->pluck('eye_colour');
		$hair_colours = User::groupBy('hair_colour')->whereNotNull('hair_colour')->pluck('hair_colour');
		$instruments = User::groupBy('instrument')->whereNotNull('instrument')->pluck('instrument');
		$gender = User::groupBy('gender')->whereNotNull('gender')->pluck('gender');
		$genre = Genres::groupBy('genre')->whereNotNull('genre')->pluck('genre');
		$acents = Accents::groupBy('accent')->whereNotNull('accent')->pluck('accent');
		
		$input   = $request->all();	

		// $country = $input['country'];
		// $age     = $input['age'];
		// $genders = $input['gender'];
		// $genres  = $input['genre'];
		// $acent  = $input['acent'];
		// $height  = $input['height'];
		// $eye_colour  = $input['eye_colour'];
		// $hair_colour  = $input['hair_colour'];
		// $instrument  = $input['instrument'];

		
		// $users = User::orWhere('users.country','LIKE','%'.$country.'%')
		// ->orWhere('users.gender',$genders)
		// ->orWhere('users.genre', 'LIKE', '%'.$genres.'%')
		// ->orWhere('users.playing_age', 'LIKE', '%'.$age.'%')
		// ->orWhere('users.acents', 'LIKE', '%'.$acent.'%')
		// ->orWhere('users.height', 'LIKE', '%'.$height.'%')
		// ->orWhere('users.height', 'LIKE', '%'.$height.'%')
		// ->orWhere('users.eye_colour', 'LIKE', '%'.$eye_colour.'%')
		// ->orWhere('users.hair_colour', 'LIKE', '%'.$hair_colour.'%')
		// ->orWhere('users.instrument', 'LIKE', '%'.$instrument.'%')
		// ->get();

		$query = User::query();
		if(Auth::user()){
			$query = $query->Where('users.id', '!=',Auth::user()->id);
		}
		if (isset($input['country']) && $input['country']!='') {
			$query = $query->Where('users.country', 'LIKE', '%'.$input['country'].'%');
		}
		if (isset($input['age']) && $input['age']!='') {
			$query = $query->Where('users.playing_age', 'LIKE', '%'.$input['age'].'%');
		}
		if (isset($input['gender']) && $input['gender']!='') {
			$query = $query->Where('users.gender', $input['gender']);
		}
		if (isset($input['genre']) && $input['genre']!='') {
			$query = $query->Where('users.genre', 'LIKE', '%'.$input['genre'].'%');
		}
		if (isset($input['acent']) && $input['acent']!='') {
			$query = $query->Where('users.acents', 'LIKE', '%'.$input['acent'].'%');
		}
		if (isset($input['height']) && $input['height']!='') {
			$query = $query->Where('users.height', 'LIKE', '%'.$input['height'].'%');
		}
		if (isset($input['eye_colour']) && $input['eye_colour']!='') {
			$query = $query->Where('users.eye_colour', 'LIKE', '%'.$input['eye_colour'].'%');
		}
		if (isset($input['hair_colour']) && $input['hair_colour']!='') {
			$query = $query->Where('users.hair_colour', 'LIKE', '%'.$input['hair_colour'].'%');
		}
		if (isset($input['instrument']) && $input['instrument']!='') {
			$query = $query->Where('users.instrument', 'LIKE', '%'.$input['instrument'].'%');
		}
		if (isset($input['crew_type']) && $input['crew_type']!='') {
			$query = $query->Where('users.crew_type', 'LIKE', '%'.$input['crew_type'].'%');
		}
		
		$searchResult = $query->select("users.*",
			DB::raw("(SELECT COUNT(likes.profile_id)  FROM likes WHERE likes.profile_id=users.id) as likes"))
		->orderby('likes','DESC')
		->paginate(40);

		// $user_ids=[];
		// foreach ($users as $key => $value) 
		// {
		// 	$user_ids[]=$value->id;
		// }

		// if(isset($user_ids) && count($user_ids)>0){

		// 	$likeusers=Like::whereIn('profile_id', $user_ids)
		// 	->selectRaw('likes.*,count(profile_id) as userlikes')
		// 	->groupBy('profile_id')
		// 	->orderby('userlikes','DESC')
		// 	->get();
		// }

		// $searchResult=[];
		// if(isset($likeusers) && count($likeusers)){
		// 	$likesids=[];
		// 	foreach ($likeusers as $key => $likeuser) 
		// 	{

		// 		$likesids[]=$likeuser->profile_id;
		// 		$likeuser->profilelike->likes=$likeuser->userlikes;
		// 		array_push($searchResult, $likeuser->profilelike);
		// 	}
		// 	foreach ($users as $key => $user) 
		// 	{
		// 		if(!in_array($user->id,$likesids)){
		// 			array_push($searchResult, $user);
		// 		}
		// 	}
		// }else{
		// 	foreach ($users as $key => $user) 
		// 	{
		// 		array_push($searchResult, $user);
		// 	}
		// }

		// foreach ($searchResult as $key => $user) 
		// {
		// 	if(Auth::user() && $user->id==Auth::user()->id){
		// 		unset($searchResult[$key]);
		// 	}
		// }	
		$count = count($searchResult);

		return view('search-profile-filter',compact('input','searchResult','userdata','gender','genre','count','acents','playing_age','input','heights','eye_colours','hair_colours','instruments','users'));
	}

	public function advanceSearchReset()
	{
		$userdata = User::groupBy('country')->whereNotNull('country')->pluck('country');
		$playing_age = User::groupBy('playing_age')->whereNotNull('playing_age')->pluck('playing_age');
		$heights = User::groupBy('height')->whereNotNull('height')->pluck('height');
		$eye_colours = User::groupBy('eye_colour')->whereNotNull('eye_colour')->pluck('eye_colour');
		$hair_colours = User::groupBy('hair_colour')->whereNotNull('hair_colour')->pluck('hair_colour');
		$instruments = User::groupBy('instrument')->whereNotNull('instrument')->pluck('instrument');
		$gender = User::groupBy('gender')->whereNotNull('gender')->pluck('gender');
		$genre = Genres::groupBy('genre')->whereNotNull('genre')->pluck('genre');
		$acents = Accents::groupBy('accent')->whereNotNull('accent')->pluck('accent');

		
		$query = User::query();
		if(Auth::user()){
			$query = $query->Where('users.id', '!=',Auth::user()->id);
		}
		$searchResult = $query->select("users.*",
			DB::raw("(SELECT COUNT(likes.profile_id)  FROM likes WHERE likes.profile_id=users.id) as likes"))
		->orderby('likes','DESC')
		->paginate(40);

		// $user_ids=[];
		// foreach ($users as $key => $value) 
		// {
		// 	$user_ids[]=$value->id;
		// }

		// $likeusers=Like::whereIn('profile_id', $user_ids)
		// ->selectRaw('likes.*,count(profile_id) as userlikes')
		// ->groupBy('profile_id')
		// ->orderby('userlikes','DESC')
		// ->get();

		// $searchResult=[];
		// if(isset($likeusers) && count($likeusers)){
		// 	$likesids=[];
		// 	foreach ($likeusers as $key => $likeuser) 
		// 	{
		// 		$likesids[]=$likeuser->profile_id;
		// 		$likeuser->profilelike->likes=$likeuser->userlikes;
		// 		array_push($searchResult, $likeuser->profilelike);
		// 	}
		// 	foreach ($users as $key => $user) 
		// 	{
		// 		if(!in_array($user->id,$likesids)){
		// 			array_push($searchResult, $user);
		// 		}
		// 	}
		// }else{
		// 	foreach ($users as $key => $user) 
		// 	{
		// 		array_push($searchResult, $user);
		// 	}
		// }
		// foreach ($searchResult as $key => $user) 
		// {
		// 	if(Auth::user() && $user->id==Auth::user()->id){
		// 		unset($searchResult[$key]);
		// 	}
		// }
		$count = count($searchResult);
		
		return view('search-profile-filter',compact('input','searchResult','userdata','gender','genre','count','acents','playing_age','heights','eye_colours','hair_colours','instruments'));
	}
}
