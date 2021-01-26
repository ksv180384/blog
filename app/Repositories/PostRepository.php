<?php


namespace App\Repositories;


use App\Models\Blog\Like;
use App\Models\Blog\Post as Model;
use App\Models\Blog\Post;
use App\Models\Blog\PostToTag;
use App\Models\Blog\Tag;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PostRepository extends CoreRepository
{
    public function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получет данные поста
     * @param $id - идентификатор пользователя
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPost($id){
        $posts = $this->startConditions()
            ->with(['comments.user'])
            ->withCount(['comments', 'likes'])
            ->where('id', '=', $id)
            ->whereNotNull('published_at')
            ->first();

        return $posts;
    }

    /**
     * Получает список постов для страницы со списком постов. Не включет в себя полную статью (content)
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getPreviewPostsList($paginate = 10){
        $posts = $this->startConditions()
            ->with(['user', 'tags', 'checkUserLike'])
            ->withCount(['comments', 'likes'])
            ->whereNotNull('published_at')->orderBy('published_at', 'desc')->paginate($paginate);

        return $posts;
    }

    /**
     * Получает список постов для страницы со списком постов. Не включет в себя полную статью (content)
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewPostsListAdm($paginate = 10){
        $posts = $this->startConditions()
            ->with(['user', 'tags', 'checkUserLike'])
            ->withCount(['comments', 'likes'])
            ->whereNotNull('published_at')->orderBy('published_at', 'desc')->paginate($paginate);

        return $posts;
    }

    /**
     * Получает список постов определенного ползователя для страницы со списком постов. Не включет в себя полную статью (content)
     * @param int $user_id - идентификатор пользователя
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewPostsListByUser($user_id, $paginate = 10){
        $posts = $this->startConditions()
            ->with(['user', 'tags', 'checkUserLike'])
            ->withCount(['comments', 'likes'])
            ->where('user_id', '=', $user_id)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate($paginate);

        return $posts;
    }

    /**
     * Получаем все посты пользователя включая неопубликованные, сортировка по дате создания
     * @param int $user_id
     * @param int $paginate
     * @return mixed
     */
    public function getPreviewPostsListByUserPublishedAll($user_id, $paginate = 10){
        $posts = $this->startConditions()
            ->with(['user', 'tags', 'checkUserLike'])
            ->withCount(['comments', 'likes'])
            ->where('user_id', '=', $user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);

        return $posts;
    }

    /**
     * Получает список постов
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPostsList($paginate = 5){
        $posts = $this->startConditions()
            ->with(['user', 'tags', 'commentsCount', 'likesCount', 'checkUserLike'])
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->orderBy('posts.published_at', 'DESC')
            ->paginate($paginate);

        return $posts;
    }

    /**
     * Получает список постов прикрепленных к определенному тегу
     * @param int $tag_id - идентификатор тега
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPostsListByTag($tag_id, $paginate = 10){

        $posts = $this->startConditions()
            ->with(['user', 'tags', 'checkUserLike'])
            ->withCount(['comments', 'likes'])
            ->join('post_to_tag', 'post_to_tag.post_id', '=', 'posts.id')
          ->where('post_to_tag.tag_id', '=', $tag_id)
          ->whereNotNull('published_at')
           ->orderBy('published_at', 'desc')->paginate($paginate);

        return $posts;
    }

    /**
     * Получить модель для редактирования в админке
     * @param $id
     * @return Post
     */
    public function getEdit($id){
        return $this->startConditions()->find($id);
    }
}