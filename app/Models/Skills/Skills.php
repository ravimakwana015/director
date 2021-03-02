<?php

namespace App\Models\Skills;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
     protected $fillable = [
    	'skill',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
