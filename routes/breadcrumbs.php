<?php
/*
// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > About
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});
*/

// Главная
Breadcrumbs::for('index', function ($trail) {
    $trail->push('Главная', route('index'));
});

// ---------------------- Пользователи -------------------

// Главная -> Список пользователей
Breadcrumbs::for('users-control', function ($trail) {
    $trail->parent('index');
    $trail->push('Пользователи', route('user.index'));
});

// Главная -> Список пользователей -> Пользователь
Breadcrumbs::for('user', function ($trail, $user) {
    $trail->parent('users-control');
    $trail->push($user->email, route('user.show', $user->id));
});

// Главная -> Список пользователей -> Добавить пользователя
Breadcrumbs::for('user-create', function ($trail) {
    $trail->parent('users-control');
    $trail->push('Добавить пользователя');
});

// ---------------------- Посты -------------------

// Главная -> Добавить пост
Breadcrumbs::for('post-create', function ($trail) {
    $trail->parent('index');
    $trail->push('Добавить пост');
});

// Главная -> Мои посты
Breadcrumbs::for('posts-my-list', function ($trail) {
    $trail->parent('index');
    $trail->push('Мои посты', route('post.my'));
});

// Главная -> Мои посты
Breadcrumbs::for('posts-tag-list', function ($trail, $tag) {
    $trail->parent('index');
    $trail->push('Тег ' . $tag->title, route('post.tag', $tag->id));
});

// Главная -> Мои посты -> Редактировать пост
Breadcrumbs::for('post-edit-my', function ($trail, $post) {
    $trail->parent('posts-my-list');
    $trail->push('Редактировать пост', route('post.edit', $post->id));
});

// ------------------ Подписан -----------
// Главная -> Подписан
    Breadcrumbs::for('follow-list', function ($trail) {
    $trail->parent('index');
    $trail->push('Подписан', route('follow'));
});

// ------------------ АДМИН -----------
// Главная -> Мои посты
Breadcrumbs::for('adm-posts-list', function ($trail) {
    $trail->parent('index');
    $trail->push('Управление постами', route('adm_post.index'));
});

// Главная -> Мои посты -> Редактировать пост
Breadcrumbs::for('adm-post-show', function ($trail, $post) {
    $trail->parent('adm-posts-list');
    $trail->push('Пост', route('adm_post.show', $post->id));
});

// Главная -> Мои посты -> Редактировать пост
Breadcrumbs::for('adm-post-edit', function ($trail, $post) {
    $trail->parent('adm-posts-list');
    $trail->push('Редактировать пост', route('adm_post.edit', $post->id));
});