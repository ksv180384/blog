<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $date = $faker->dateTimeInInterval('-1 days', '-30 days');
    return [
        'user_id' => function(){
            return \App\Models\User\User::orderByRaw("RAND()")->first()->id;
        },
        'title' => $faker->text(100),
        'excerpt' => $faker->text(400),
        'content' => $faker->text(2000),
        'published_at' => $date,
        'created_at' => $date,
        'updated_at' => $date,
    ];

});
