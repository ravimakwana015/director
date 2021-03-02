<?php

namespace App\Models\Genres;

use Illuminate\Database\Eloquent\Model;

class Genres extends Model
{
    protected $fillable = [
    	'genre',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
