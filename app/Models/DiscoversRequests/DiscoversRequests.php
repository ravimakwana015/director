<?php

namespace App\Models\DiscoversRequests;

use Illuminate\Database\Eloquent\Model;
use App\Models\Discovers\Discovers;
use App\User;

class DiscoversRequests extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    	'discover_id',
    	'profile_name',
    	'email',
    	'cover_letter',
    	'cv',
    	'status',
    ];
    protected $dates = [
    	'created_at',
    	'updated_at',
    ];

    public function discovers()
    {
        return $this->belongsTo(Discovers::class,'discover_id','id');
    }

    public function usersid()
    {
    	return $this->belongsTo(User::class,'user_id','id');
    }
}
