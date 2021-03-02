<?php

namespace App\Models\Invites;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Invites extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'message',
        'token',
        'status',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function referuser()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
