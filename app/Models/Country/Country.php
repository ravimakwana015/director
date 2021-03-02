<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
   public $fillable = ['name'];
   protected $dates = [
    	'created_at',
    	'updated_at',
    ];
}
