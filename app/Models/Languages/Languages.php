<?php

namespace App\Models\Languages;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
     protected $fillable = [
    	'language',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
