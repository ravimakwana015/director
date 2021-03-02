<?php

namespace App\Models\RoleTypes;

use Illuminate\Database\Eloquent\Model;

class RoleTypes extends Model
{
     protected $fillable = [
    	'role',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
