<?php

namespace App\Models\Blog;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package App\Models\Blog
 * @property int user_id
 * @property int post_id
 * @property string comment
 * @property User user
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
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

    public function user(){
        return $this->belongsTo(User::class);
    }
}
