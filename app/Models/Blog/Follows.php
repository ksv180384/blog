<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Follows extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_user_id',
        'to_user_id',
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
     * Пользователь который подписался
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userFrom(){
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    /**
     * Пользователь на которого подписались
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTo(){
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }
}
