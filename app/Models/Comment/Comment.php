<?php

namespace App\Models\Comment;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Forums\Forums;

class Comment extends Model
{
     protected $fillable = [
        'topic_id',
        'user_id',
        'comment',
        'comment_status',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function usercomment()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function forumtopic()
    {
        return $this->belongsTo(Forums::class,'topic_id', 'id');
    }
}
