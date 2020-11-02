<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Удаляет все теги привязанные к посту
     * @param int $post_id - идентификатор поста
     * @return mixed
     * @throws \Exception
     */
    public static function deleteTagsByPost($post_id){
        return self::where('post_id', '=', $post_id)->delete();
    }
}
