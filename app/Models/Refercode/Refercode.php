<?php

namespace App\Models\Refercode;

use Illuminate\Database\Eloquent\Model;

class Refercode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'friend_name',
    	'friend_email',
    	'refer_code',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
