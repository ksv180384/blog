<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $date = $faker->dateTimeInInterval('-30', '-1');

    return [
        'user_id' => function(){
            return \App\Models\User\User::orderByRaw("RAND()")->first()->id;
        },
        'title' => trim($faker->text(14), '.'),
        'active' => 1,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
