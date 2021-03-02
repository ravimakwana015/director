<?php

namespace App\Models\ExplorepageSEO;

use Illuminate\Database\Eloquent\Model;

class ExplorepageSeo extends Model
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
