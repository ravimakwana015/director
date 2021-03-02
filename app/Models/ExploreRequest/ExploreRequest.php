<?php

namespace App\Models\ExploreRequest;

use Illuminate\Database\Eloquent\Model;
use App\Models\Explore\Explore;
use App\User;

class ExploreRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'explore_id',
        'user_id',
        'profile_name',
        'email',
        'cover_letter',
        'name',
        'phone',
        'status',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function explore()
    {
        return $this->belongsTo(Explore::class,'explore_id','id');
    }

    public function exploreuser()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
