<?php

namespace App\Models\UsersPersonalityTraits;

use Illuminate\Database\Eloquent\Model;

class UsersPersonalityTraits extends Model
{
    protected $fillable = [
    	'user_id',
    	'loneliness',
    	'entertainment',
    	'curiosity',
    	'relationship',
    	'hookup',
    	'click_by',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
