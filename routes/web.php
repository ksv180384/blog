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
    $methods = ['index', 'show', 'update', 'edit']; // Используемые методы
    Route::resource('profile', 'ProfileController')
        ->only($methods)
        ->names('profile');
    //Route::post('profile/updateProfile', 'ProfileController@update')->name('profile.updateProfile');
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
    // Удаляем - добавляем лайк
    Route::post('post/toggle-like/{id}', 'PostController@toggleLike')->name('post.toggle_like');

    $methods = ['create', 'store', 'edit', 'update', 'show'];
    Route::resource('post', 'PostController')
        ->only($methods)
        ->names('post');

    Route::group(['prefix' => 'posts'], function (){
        // Отслеживание(подписка на посты) постов
        Route::get('follow', 'FallowController@index')->name('follow');
        Route::get('tag/{id}', 'PostController@postsByTag')->name('post.tag');

        Route::post('follow/add', 'FallowController@add')->name('follow.add');
        Route::post('follow/destroy/{to_user_id}', 'FallowController@destroy')->name('follow.destroy');

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

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

//Create storage link
Route::get('/storage-link', function() {
    $exitCode = Artisan::call('storage:link');
    return '<h1>Create storage link</h1>';
});


