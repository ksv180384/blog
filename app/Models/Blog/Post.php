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
 * @property User user
 * @property Tag tags
 * @property Comment[] comments
 * @property Like[] likes
 * @property boolean checkUserLike
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

    /**
     * Лайки
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes(){
        return $this->hasMany(Like::class, 'post_id', 'id');
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
     * Показываем пост убирая тег script
     *
     * @return void
     */
    public function getShowHtmlContentAttribute()
    {
        $htmlContent = str_replace('<script', '&lt;script', $this->content);
        $htmlContent = str_replace('</script', '&lt;/script', $htmlContent);

        return $htmlContent;
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
