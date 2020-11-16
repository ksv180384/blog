<?php

namespace App\Observers\Blog;

use App\Http\Controllers\Blog\PostController;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Blog\Post;

class PostObserver
{
    /**
     * Изменение модели перед созданием (записью в БД)
     *
     * @param  Post $post
     * @return void
     */
    public function creating(Post $post)
    {
        //
    }

    /**
     * Изменение модели после созданием (записью в БД)
     *
     * @param  Post $post
     * @return void
     */
    public function created(Post $post)
    {
        //
    }

    /**
     * Изменение модели перед обновлением
     *
     * @param  Post $post
     * @param  PostUpdateRequest $request
     * @return void
     */
    public function updating(Post $post)
    {
        //

    }

    /**
     * Изменение модели после обновлением
     *
     * @param  Post $post
     * @return void
     */
    public function updated(Post $post)
    {
        //

    }

    /**
     * Изменение модели перед сохранением
     *
     * @param  Post $post
     * @return void
     */
    public function saving(Post $post)
    {
        //
    }

    /**
     * Изменение модели после сохранения
     *
     * @param  Post $post
     * @return void
     */
    public function saved(Post $post)
    {
        //
    }

    /**
     * Изменение модели перед удалением
     *
     * @param  Post $post
     * @return void
     */
    public function deleting(Post $post)
    {
        //
    }

    /**
     * Изменение модели после удаления
     *
     * @param  Post $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //
    }

    /**
     * Изменение модели перед восстановлением
     *
     * @param  Post $post
     * @return void
     */
    public function restoring(Post $post)
    {
        //
    }

    /**
     * Изменение модели после восстановления
     *
     * @param  Post $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }
}
