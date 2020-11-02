<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'avatar_select',
        'sex',
        'birthday',
        'residence',
        'description',
        'view_name',
        'view_sex',
        'view_birthday',
        'view_residence',
        'view_description',
        'show_yar_birthday',
        'view_sub_characters',
        'date_active',
        'date_registration',
        'adm',
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
    ];

    /**
     * Пол пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sex(){
        return $this->belongsTo(UserSex::class, 'sex', 'id');
    }

    /**
     * Статус пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(){
        return $this->belongsTo(UserStatus::class, 'status', 'id');
    }

    /**
     * Установить аватар пользователя.
     *
     * @return void
     */
    public function getAvatarAttribute($avatar)
    {
        if(empty($avatar)){
            $avatar = asset(self::NO_AVATAR);
        }else{
            $avatar = asset('/storage/' . $avatar);
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

    public static function activeAvatar($avatar = null){
        if(empty($avatar)){
            $avatar = asset(self::NO_AVATAR);
        }else{
            $avatar = asset('/storage/' . $avatar);
        }

        return $avatar;
    }
}
