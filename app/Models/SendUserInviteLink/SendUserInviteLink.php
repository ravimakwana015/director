<?php

namespace App\Models\SendUserInviteLink;

use Illuminate\Database\Eloquent\Model;

class SendUserInviteLink extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
     	'email',
     	'code',
     	'status',
     ];
     protected $dates = [
     	'created_at',
     	'updated_at',
     ];
 }
