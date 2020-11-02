<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        //$this->call(\App\Models\Blog\Post::class);

        // Фабрика создает 40 случайных пользователей
        factory(\App\Models\User\User::class, 40)->create()->each(function($user) {
            $user->assignRole(['Пользователь']);
        });

        // Фабрика создает 400 случайных постов
        factory(\App\Models\Blog\Post::class, 400)->create();

        // Фабрика создает 20 случайных тегов
        factory(\App\Models\Blog\Tag::class, 20)->create();

        // Фабрика присваивает теги постам
        factory(\App\Models\Blog\PostToTag::class, 500)->create();
    }
}
