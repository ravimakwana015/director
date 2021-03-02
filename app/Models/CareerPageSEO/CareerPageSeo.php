<?php

namespace App\Models\CareerPageSEO;

use Illuminate\Database\Eloquent\Model;

class CareerPageSeo extends Model
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
