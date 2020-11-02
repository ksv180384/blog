<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sex', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias');
        });

        Schema::create('user_status', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->integer('avatar_select')->default(1)->comment('Выбор аватала для отображения 1 - аватар пользователя; 2 - аватар персонажа');
            $table->bigInteger('sex')->unsigned()->nullable()->comment('Пол');
            $table->timestamp('birthday')->nullable()->comment('Дата рождения');
            $table->string('residence')->nullable()->comment('Место проживания');
            $table->text('description')->nullable()->comment('О себе');
            $table->integer('view_name')->default(1)->comment('Ограничиваем пользователей которым показываем имя');
            $table->integer('view_sex')->default(1)->comment('Ограничиваем пользователей которым показываем пол');
            $table->integer('view_birthday')->default(1)->comment('Ограничиваем пользователей которым показываем дату рождения');
            $table->integer('view_residence')->default(1)->comment('Ограничиваем пользователей которым показываем место жительства');
            $table->integer('view_description')->default(1)->comment('Ограничиваем пользователей которым показываем о себе');
            $table->integer('show_yar_birthday')->default(0)->comment('Показывать ли год рождения другим пользователям');
            $table->integer('view_sub_characters')->default(1)->comment('Показывать ли дополнительных персонажей пользователя (твинов)');
            $table->timestamp('date_active')->nullable();
            $table->timestamp('date_registration')->nullable();
            $table->integer('adm')->default(0)->comment('Главный администратор');
            $table->bigInteger('status')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('sex', 'sex_index');
            $table->index('status', 'status_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('sex')->references('id')->on('user_sex');
            $table->foreign('status')->references('id')->on('user_status');
        });

        // Указываем список полов пользователей
        $sex = [
            [
                'title' => 'Мужской',
                'alias' => \Illuminate\Support\Str::slug('Мужской'),
            ],
            [
                'title' => 'Женский',
                'alias' => \Illuminate\Support\Str::slug('Женский'),
            ],
        ];

        // Указываем список статусов пользователей
        $user_status = [
            [
                'title' => 'Активный игрок',
                'alias' => \Illuminate\Support\Str::slug('Активный игрок'),
            ],
            [
                'title' => 'Низкий онлайн',
                'alias' => \Illuminate\Support\Str::slug('Низкий онлайн'),
            ],
            [
                'title' => 'Не играет',
                'alias' => \Illuminate\Support\Str::slug('Не играет'),
            ],
            [
                'title' => 'Не состоит в гильдии',
                'alias' => \Illuminate\Support\Str::slug('Низкий онлайн'),
            ],
        ];

        // Указываем список пользователей
        /*
        $users = [
            [
                'name' => null,
                'email' => 'ksv180384@yandex.ru',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
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
        */

        \Illuminate\Support\Facades\DB::table('user_sex')->insert($sex);
        \Illuminate\Support\Facades\DB::table('user_status')->insert($user_status);
        //\Illuminate\Support\Facades\DB::table('users')->insert($users);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('user_sex');
        Schema::drop('user_status');
    }
}
