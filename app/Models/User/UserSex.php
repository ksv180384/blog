<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

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
