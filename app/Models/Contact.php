<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name','email','contact_number','message'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
