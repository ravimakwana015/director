<?php

namespace App\Models\Explore;

use Illuminate\Database\Eloquent\Model;

class Explore extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'icon',
        'job_type',
        'status',
        'location',
        'country',
        'link',
        'workshop_image',
        'slug',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
