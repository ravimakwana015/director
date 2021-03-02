<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\BroadcastChat;
use App\User;

class Chat extends Model
{
    protected $dispatchesEvents = [
        'created' => BroadcastChat::class
    ];

    protected $fillable = ['user_id', 'friend_id', 'chat'];

    public function chatUser()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}
