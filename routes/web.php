<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('IndexController@index');
});
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'IndexController@index')->name('index');

// Профиль пользователя
Route::group(['namespace' => 'User', 'prefix' => 'users'], function(){
    //$methods = ['index', 'edit', 'store', 'update', 'create']; // Используемые методы
    $methods = ['index', 'show']; // Используемые методы
    Route::resource('profile', 'ProfileController')
        ->only($methods)
        ->names('profile');
    Route::post('profile/updateProfile', 'ProfileController@updateProfile')->name('profile.updateProfile');
    Route::post('profile/updateAvatar', 'ProfileController@updateAvatar')->name('profile.updateAvatar');


    Route::get('user/auth/{id}', 'UserController@authUser')->name('user.auth');
});

// Управление пользователями
Route::group(['namespace' => 'User', 'prefix' => 'users-control'], function(){
    // Управление пользователями
    Route::resource('user', 'UserController')->names('user');

    Route::post('user/controlUpdate', 'UserController@controlUpdate')->name('user.controlUpdate');
    Route::post('user/changePassword', 'UserController@changePassword')->name('user.changePassword');
});
// Роли пользователей
Route::resource('roles', 'RoleController')->names('roles');

// Блог
Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function (){
    // Посты текущего пользователя
    Route::get('post/my', 'PostController@my')->name('post.my');
    // Лайк посту
    Route::post('post/like-add/{id}', 'PostController@addLike')->name('post.like-add');
    // Удаляет лайк посту
    Route::post('post/like-remove/{id}', 'PostController@removeLike')->name('post.like-remove');

    $methods = ['create', 'store', 'edit', 'update', 'show'];
    Route::resource('post', 'PostController')
        ->only($methods)
        ->names('post');

    Route::group(['prefix' => 'posts'], function (){
        // Отслеживание(подписка на посты) постов
        Route::get('follow', 'FallowController@list')->name('follow');
        Route::get('tag/{id}', 'PostController@postsByTag')->name('post.tag');

        Route::post('follow/add', 'FallowController@add')->name('follow.add');
        Route::post('follow/destroy/{id}', 'FallowController@destroy')->name('follow.destroy');

        // Комментарии к постам
        Route::post('comment/add', 'CommentController@store')->name('comment.add');
    });

    Route::group(['prefix' => 'adm'], function (){
        Route::post('post/published/{id}', 'Admin\PostController@published')->name('post.published');

        Route::group(['namespace' => 'Admin', 'prefix' => 'posts'], function (){
            $methods = ['index', 'show', 'update', 'edit'];
            Route::resource('post', 'PostController')
                ->only($methods)
                ->names('adm_post');
        });
    });
});


