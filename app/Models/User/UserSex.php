<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSex
 * @package App\Models\User
 * @property int id
 * @property string title
 * @property string alias
 */
class UserSex extends Model
{
    //
    protected $fillable = [
        'title',
        'alias',
    ];

    protected $table = 'user_sex';

    public $timestamps = false;
}
