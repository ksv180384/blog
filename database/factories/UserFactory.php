<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'avatar' => null,
        'avatar_select' => 1,
        'sex' => null,
        'birthday' => null,
        'residence' => null,
        'description' => null,
        'view_name' => 1,
        'view_sex' => 1,
        'view_birthday' => 1,
        'view_residence' => 1,
        'view_description' => 1,
        'show_yar_birthday' => 0,
        'view_sub_characters' => 1,
        'date_active' => null,
        'date_registration' => now(),
        'adm' => 0,
        'status' => null,
        'remember_token' => Str::random(10),
    ];
});
