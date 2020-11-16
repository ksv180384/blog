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
            $table->bigInteger('sex')->unsigned()->nullable()->comment('Пол');
            $table->timestamp('birthday')->nullable()->comment('Дата рождения');
            $table->string('residence')->nullable()->comment('Место проживания');
            $table->text('description')->nullable()->comment('О себе');
            $table->integer('adm')->default(0)->comment('Главный администратор');
            $table->bigInteger('status')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('sex', 'sex_index');
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



        \Illuminate\Support\Facades\DB::table('user_sex')->insert($sex);
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
    }
}
