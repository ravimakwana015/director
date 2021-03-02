<?php

namespace App\Models\CareerRequest;

use Illuminate\Database\Eloquent\Model;
use App\Models\Career\Career;
use App\User;

class CareerRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'career_id',
        'user_id',
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

    public function career()
    {
        return $this->belongsTo(Career::class,'career_id','id');
    }

    public function careeruser()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
