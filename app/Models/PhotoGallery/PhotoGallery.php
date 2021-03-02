<?php

namespace App\Models\PhotoGallery;

use Illuminate\Database\Eloquent\Model;

class PhotoGallery extends Model
{
    protected $fillable = [
    	'user_id',
    	'image',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
