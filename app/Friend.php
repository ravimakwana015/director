<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Friend extends Model
{
    protected $fillable = [
        'user_id', 'friend_id','is_report','report_by','reason'
    ];
    public function userFriend()
    {
        return $this->belongsTo(User::class,'friend_id', 'id');
    }
    public function reportBy()
    {
        return $this->belongsTo(User::class,'report_by', 'id');
    }
}
