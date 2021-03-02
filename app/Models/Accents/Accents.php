<?php

namespace App\Models\Accents;

use Illuminate\Database\Eloquent\Model;

class Accents extends Model
{
    protected $fillable = [
    	'accent',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
