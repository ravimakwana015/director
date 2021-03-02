<?php

namespace App\Models\CareerSEO;

use Illuminate\Database\Eloquent\Model;

class CareerSeo extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'seo_title',
    	'seo_keyword',
    	'seo_description',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
