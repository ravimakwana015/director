<?php

namespace App\Models\VideoGallery;

use Illuminate\Database\Eloquent\Model;

class VideoGallery extends Model
{
   protected $fillable = [
    	'user_id',
    	'video',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}