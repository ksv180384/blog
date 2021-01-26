<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PostToTag
 * @package App\Models\Blog
 * @property int post_id
 * @property int tag_id
 * @property Post post
 * @property Tag teg
 */
class PostToTag extends Model
{
    public $table = 'post_to_tag';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'tag_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = false;

    /**
     * Пост
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function post(){
        return $this->hasOne(Post::class, 'post_id', 'id');
    }

    /**
     * Тег
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teg(){
        return $this->hasOne(Tag::class, 'tag_id', 'id');
    }
}
