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

    public $timestamps = true;

    /**
     * Пользователь
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
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
            $img = asset('img/no-image.png');
        }else{
            $img = asset('/storage/' . $img);
        }

        return $img;
    }
}
