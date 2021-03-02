<?php

namespace App\Models\Forums;

use Illuminate\Database\Eloquent\Model;
use App\Models\ForumCategory\ForumCategory;
use App\User;
use App\Models\Comment\Comment;

class Forums extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'topic',
        'topic_subject',
        'status',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function forumCategory()
    {
        return $this->belongsTo(ForumCategory::class,'category_id','id');
    }

    public function username()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

     public function comments()
    {
        return $this->hasMany(Comment::class,'topic_id', 'id');
    }

}
