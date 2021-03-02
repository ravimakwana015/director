<?php

namespace App\Models\UserFeedsLikes;
use App\Models\UserFeed\UserFeed;
use Illuminate\Database\Eloquent\Model;

class UserFeedsLikes extends Model
{
	protected $fillable =
	[
		"feed_id",
		"friend_id",
	];
	protected $dates = 
	[
		'created_at',
		'updated_at',
	];
	public function post()
	{
		return $this->belongsTo(UserFeed::class,'feed_id', 'id');
	}
}
