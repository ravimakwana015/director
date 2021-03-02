<?php

namespace App\Models\AccessCode;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AccessCode extends Model
{
	protected $fillable = [
		'code',
		'description',
		'status',
	];
	protected  $dates = [
		'created_at',
		'updated_at',
	];

	public function getcountcode()
	{
		return $this->belongsTo(User::class,'code', 'access_code');
	}

	public function codeaccess()
    {
        return $this->belongsTo(User::class,'code', 'access_code');
    }
}

