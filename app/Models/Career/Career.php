<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Model;
use App\Models\CareerRequest\CareerRequest;

class Career extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'icon',
        'job_type',
        'status',
        'location',
        'country',
        'slug',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function careerRequest()
    {
        return $this->hasMany(CareerRequest::class,'career_id');
    }
}
