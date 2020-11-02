<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'name' => 'Сергей Васильевич Котелевский',
                'email' => 'ksv180384@yandex.ru',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => \Illuminate\Support\Str::random(10),
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
            ],
        ];

        DB::table('users')->insert($users);
    }
}
