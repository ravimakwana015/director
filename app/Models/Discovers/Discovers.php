<?php

namespace App\Models\Discovers;

use Illuminate\Database\Eloquent\Model;
use App\Models\DiscoversRequests\DiscoversRequests;
use App\Models\EnterResults\EnterResults;

class Discovers extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'competitions',
        'title',
        'description',
        'icon',
        'status',
        'country',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getCompetitionRequest(){
       return $this->hasMany(DiscoversRequests::class,'discover_id');
    }
    public function getResult(){
       return $this->belongsTo(EnterResults::class,'id','enter_id');
    }
}
