<?php

namespace App\Models\UserNetwork;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserNetwork extends Model
{
	protected $fillable = [
		"user_id",
		"friend_id",
		"report_by",
		"reason",
		"status"
	];
	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function username()
    {
        return $this->belongsTo(User::class,'friend_id', 'id');
    }
}
