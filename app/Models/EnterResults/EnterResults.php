<?php

namespace App\Models\EnterResults;

use Illuminate\Database\Eloquent\Model;
use App\User;

class EnterResults extends Model
{
    protected  $guarded = [];
    protected $dates = array(
        'created_at',
        'updated_at',
    );

      public function usersid()
    {
    	return $this->belongsTo(User::class,'user_id','id');
    }
}
