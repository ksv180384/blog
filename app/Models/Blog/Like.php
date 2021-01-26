<?php

namespace App\Models\Blog;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Like
 * @package App\Models\Blog
 * @property int post_id
 * @property int user_id
 * @property User user
 */
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
     * Пользователь
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
