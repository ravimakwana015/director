<?php

namespace App\Models\Contactinquire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Contactinquire extends Model
{
    use SoftDeletes; 
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'name',
    	'company',
    	'subject',
    	'email',
    	'message',
        'facebook',
        'linkedin',
        'instagram',
        'photo',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
