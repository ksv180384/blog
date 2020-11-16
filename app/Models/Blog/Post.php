<?php

namespace App\Models\Blog;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * @package App\Models\Blog
 * @property int $id
 * @property int $user_id
 * @property string $img
 * @property string $title
 * @property string $excerpt
 * @property string $content
 * @property Carbon $published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'img',
        'title',
        'excerpt',
        'content',
        'published_at',
        'created_at',
        'updated_at',
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
    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    // Жадная загрузка поумодчанию
    protected $with = ['user', 'tags', 'commentsCount', 'likesCount', 'checkUserLike'];

    /**
     * Пользователь
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'post_to_tag', 'post_id', 'tag_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function commentsCount(){
        return $this->hasOne(Comment::class)
            ->selectRaw('post_id, count(*) as q')
            ->groupBy('post_id');
    }

    /**
     * Лайки
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes(){
        return $this->hasMany(Like::class, 'post_id', 'id');
    }

    /**
     * Считаем лайки
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Illuminate\Database\Query\Builder
     */
    public function likesCount(){
        return $this->hasOne(Like::class)
            ->selectRaw('post_id, count(*) as q')
            ->groupBy('post_id');
    }

    /**
     * Проверяем наличие лайка пользователя к посту
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function checkUserLike(){
        return $this->hasOne(Like::class)
            ->selectRaw('post_id, count(*) as q')
            ->where('user_id', '=', (\Auth::check() ? \Auth::id() : 0))
            ->groupBy('post_id');
    }

    /**
     * Количество коментариев
     * @return int
     */
    public function getCommentsCountAttribute(){
        if ( !array_key_exists('commentsCount', $this->relations)){
            $this->load('commentsCount');
        }

        $related = $this->getRelation('commentsCount');

        return ($related) ? (int) $related->q : 0;
    }

    /**
     * Количество лайков
     * @return int
     */
    public function getLikesCountAttribute(){
        if ( !array_key_exists('likesCount', $this->relations)){
            $this->load('likesCount');
        }

        $related = $this->getRelation('likesCount');

        return ($related) ? (int) $related->q : 0;
    }

    /**
     * Проверяем наличие лайка пользователя к посту
     * @return int
     */
    public function getCheckUserLikeAttribute(){
        if ( !array_key_exists('checkUserLike', $this->relations)){
            $this->load('checkUserLike');
        }

        $related = $this->getRelation('checkUserLike');

        return ($related) ? (boolean) $related->q : false;
    }

    /**
     * Установить аватар пользователя.
     *
     * @param string $img - ссылка на картинку
     * @return void
     */
    public function getImgAttribute($img)
    {
        if(empty($img)){
            $img = 'no-image.png';
        }

        return $img;
    }

}
