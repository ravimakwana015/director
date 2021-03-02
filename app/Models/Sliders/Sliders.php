<?php

namespace App\Models\Sliders;

use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    	'image',
    	'status',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];

}
