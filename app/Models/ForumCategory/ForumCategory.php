<?php

namespace App\Models\ForumCategory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forums\Forums;

class ForumCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function categoryTopics()
    {
        return $this->hasMany(Forums::class, 'category_id', 'id');
    }
}
