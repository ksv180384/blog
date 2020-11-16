<?php

namespace App\Models\User;

use App\Models\Blog\Follows;
use App\Models\Blog\Post;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    const NO_AVATAR = '/img/no-avatar.png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'sex',
        'birthday',
        'residence',
        'description',
        'adm',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Жадная загрузка поумодчанию
    protected $with = ['role'];

    /**
     * Пол пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sexData(){
        return $this->belongsTo(UserSex::class, 'sex', 'id');
    }

    public function role(){
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    /**
     * Количество подписчиков
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Illuminate\Database\Query\Builder
     */
    public function followToCount(){
        return $this->hasOne(Follows::class, 'to_user_id')
            ->selectRaw('to_user_id, count(*) as q')
            ->groupBy('to_user_id');
    }

    /**
     * Количество коментариев
     * @return int
     */
    public function getFollowToCountAttribute(){
        if ( !array_key_exists('followToCount', $this->relations)){
            $this->load('followToCount');
        }

        $related = $this->getRelation('followToCount');

        return ($related) ? (int) $related->q : 0;
    }

    /**
     * Количество подписчиков
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Illuminate\Database\Query\Builder
     */
    public function followFromCount(){
        return $this->hasOne(Follows::class, 'from_user_id')
            ->selectRaw('from_user_id, count(*) as q')
            ->groupBy('from_user_id');
    }

    /**
     * Количество подписок
     * @return int
     */
    public function getFollowFromCountAttribute(){
        if ( !array_key_exists('followFromCount', $this->relations)){
            $this->load('followFromCount');
        }

        $related = $this->getRelation('followFromCount');

        return ($related) ? (int) $related->q : 0;
    }

    /**
     * Установить аватар пользователя.
     *
     * @return void
     */
    public function getAvatarAttribute($avatar)
    {
        if(empty($avatar)){
            $avatar = 'no-avatar.png';
        }

        return $avatar;
    }

    /**
     * Установить пол пользователя.
     *
     * @return void
     */
    public function getSexTitleAttribute($sex_title)
    {
        if(empty($sex_title)){
            $sex_title = 'Нет';
        }

        return $sex_title;
    }
}
