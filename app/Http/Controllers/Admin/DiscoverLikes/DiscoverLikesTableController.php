<?php

namespace App\Http\Controllers\Admin\DiscoverLikes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DiscoversRequests\DiscoversRequests;
use App\Models\Like\Like;
use App\Notifications\LikeNotification;
use App\User;
use Carbon\Carbon;

class DiscoverLikesTableController extends Controller
{
	public function __invoke()
	{

		return Datatables::make($this -> getForDataTable())
		->addColumn('created_at', function ($discoverlike) {
			return Carbon::parse($discoverlike->created_at)->format('d/m/Y H:i:s');
		}) ->addColumn('updated_at', function ($discoverlike) {
			return Carbon::parse($discoverlike->updated_at)->format('d/m/Y H:i:s');
		})
		->addColumn('discover', function($discoverlike)
		{
			return $discoverlike->discovers->title;
		})
		->addColumn('competitions', function($discoverlike)
		{
			return $discoverlike->discovers->competitions;
		})
		->addColumn('profile_name', function($discoversrequest)
		{
			return '<a href="'.route('users.show',$discoversrequest->usersid->id).'">'.$discoversrequest->usersid->first_name.' '.$discoversrequest->usersid->last_name.'</a>';
			//return $topic->username->first_name;
		})
		->addColumn('actions', function($discoverlike)
		{
			$likeduser = Like::where('user_id',$discoverlike->usersid->id)->where('profile_id',$discoverlike->usersid->id)->where('discover_id',$discoverlike->discovers->id)->where('like_user_type','admin')->first();
			if(!empty($likeduser))
			{
				return '<button class="button button5 likeprofile_'.$discoverlike->id.'" type="button" id="unlikeprofile" style="color: red;" onclick="likeprofile('.$discoverlike->usersid->id.','.$discoverlike->discovers->id.','.$discoverlike->id.')"><i class="fas fa-thumbs-up"></i></button>';
			}
			else
			{
				return '<button class="button button5 likeprofile_'.$discoverlike->id.'" type="button" id="likeprofile" onclick="likeprofile('.$discoverlike->usersid->id.','.$discoverlike->discovers->id.','.$discoverlike->id.')"><i class="far fa-thumbs-up"></i></button>';
			}
		})
		->rawColumns(['status','actions','profile_name'])
		->make(true);
		// <a href="'.route('discoversrequest.edit',$discoverlike->id).'" class="btn btn-primary">Edit</a>
	}

	/*
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the Career getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        return DiscoversRequests ::query() -> leftjoin('users as u', 'u.id', '=', 'discovers_requests.user_id') -> where('u.deleted_at', null)
            -> select(['discovers_requests.*']);
    }

	public function likeprofiles(Request $request)
	{
		$input = $request->all();
		$likeduser = Like::where('user_id',$input['user_id'])->where('profile_id',$input['user_id'])->where('discover_id',$input['discover_id'])->where('like_user_type','admin')->first();
		if(!empty($likeduser))
		{
			$likeduser->delete();
			echo json_encode(false);
		}
		else
		{
			Like::create([
				'user_id'    => $input['user_id'],
				'profile_id' => $input['user_id'],
				'like_user_type' => 'admin',
				'ip_address' => request()->ip(),
				'discover_id' => $input['discover_id']
			]);
			$user=User::find($input['user_id']);
			$msg='Admin like your profile for winning Competitions';
    		$user->notify(new LikeNotification($msg));
			echo json_encode(true);
		}
	}
}


