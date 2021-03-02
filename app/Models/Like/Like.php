<?php

namespace App\Models\Like;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Like extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'profile_id',
        'like_user_type',
        'ip_address',
        'discover_id',
        'count',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function userlike()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function profilelike()
    {
        return $this->belongsTo(User::class,'profile_id', 'id');
    }
}
