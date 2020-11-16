<?php

namespace App\Providers;

use App\Models\Blog\Post;
use App\Observers\Blog\PostObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Post::observe(PostObserver::class);

        // создание валидации «only_user»
        // Проверяет существует ли уже поле с такими данными, если существует, то
        // проверяет принадлежит ли это поле текущему пользователю, если да, то валидация прошла успешно
        \Validator::extend('only_user', function ($attribute, $value, $parameters, $validator) {

            //var_export(\Auth::check());
            $r = \DB::table('users')
                ->select(\DB::raw('COUNT(*) as count'))
                ->where($attribute, '=', $value)
                ->first();

            if($r->count > 1){
                return false;
            }
            $user = \DB::table('users')
                ->select('id')
                ->where($attribute, '=', $value)
                ->first();
            if(!empty($user->id) && $user->id != \Auth::id()){
                return false;
            }
            return true;
        });
    }
}
