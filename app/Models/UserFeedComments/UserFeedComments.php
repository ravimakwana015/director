<?php

namespace App\Models\UserFeedComments;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\UserFeed\UserFeed;

class UserFeedComments extends Model
{
	protected $fillable =
	[
		"feed_id",
		"friend_id",
		"comment",
	];
	protected $dates = 
	[
		'created_at',
		'updated_at',
	];

	public function commentOwner()
	{
		return $this->belongsTo(User::class,'friend_id', 'id');
	}
	public function feedOwner()
	{
		return $this->belongsTo(UserFeed::class,'feed_id', 'id');
	}
}
