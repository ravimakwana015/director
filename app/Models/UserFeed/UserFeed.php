<?php

namespace App\Models\UserFeed;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserFeedsLikes\UserFeedsLikes;
use App\Models\UserFeedComments\UserFeedComments;

class UserFeed extends Model
{
	protected $fillable = [
		"user_id",
		"feed",
		"properties",
		"feed_type",
	];
	protected $dates = [
		'created_at',
		'updated_at',
	];
	public function postOwner()
	{
		return $this->belongsTo(User::class,'user_id', 'id');
	}

	public function postLike()
	{
		return $this->hasMany(UserFeedsLikes::class,'feed_id', 'id');
	}

	public function postcomment()
	{
		return $this->hasMany(UserFeedComments::class,'feed_id', 'id');
	}
}
