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
                'sex' => null,
                'birthday' => null,
                'residence' => null,
                'description' => null,
                'adm' => 0,
                'status' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
