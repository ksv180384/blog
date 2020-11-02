<?php

namespace App\Models\Blog;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'user_id',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    /**
     * Тег
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    /**
     * Проверяет сьавил ли пользователь лайк посту
     * @param int $post_id - идентификатор поста
     * @param int $user_id - идентификатор пользователя
     * @return bool
     */
    public static function checkLike(int $post_id, int $user_id = 0){
        return self::where('post_id', '=', $post_id)->where('user_id', '=', $user_id)->first() ? true : false;
    }
}
