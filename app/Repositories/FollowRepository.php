<?php


namespace App\Repositories;


use App\Models\Blog\Follows as Model;
use App\Models\Blog\Follows;
use App\Models\Blog\Post;
use App\Models\Blog\PostToTag;
use App\Models\Blog\Tag;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class FollowRepository extends CoreRepository
{
    public function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получет посты пользоветелей на которых подписан
     * @param $user_id - идентификатор пользователя
     * @param $paginate - количество выводимых постов на одной странице
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFollowsPostsByUser(int $user_id, int $paginate = 5){

        $columns = [
            'posts.id',
            'posts.user_id',
            'posts.img',
            'posts.title',
            'posts.excerpt',
            'posts.content',
            'posts.published_at',
            'posts.created_at',
            'posts.updated_at',
            'users.name',
            'users.avatar',
            'users.avatar_select',
            'users.view_name',
            'users.view_sex',
            'users.date_active',
            'users.date_registration',
            'users.adm',
            \DB::raw('(SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS likes_count'),
            \DB::raw('(SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comments_count'),
        ];

        $posts = $this->startConditions()
            ->select($columns)
            ->join('posts', 'posts.user_id', '=', 'follows.to_user_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('follows.from_user_id', '=', $user_id)
            ->paginate($paginate);

        foreach ($posts as $k => $post){
            $posts[$k]->avatar = User::activeAvatar($post->avatar);
        }

        return $posts;
    }

    public function getFollowsByUser(int $user_id){
        $columns = [
            'follows.id AS follow_id',
            'users.id',
            'users.id',
            'users.name',
            'users.avatar',
            'users.avatar_select',
            'users.view_name',
            'users.view_sex',
            'users.date_active',
            'users.date_registration',
            'users.adm',
        ];

        $users = $this->startConditions()
            ->select($columns)
            ->join('users', 'users.id', '=', 'follows.to_user_id')
            ->where('follows.from_user_id', '=', $user_id)
            ->get();

        foreach ($users as $k => $post){
            $users[$k]->avatar = User::activeAvatar($post->avatar);
        }
        return $users;
    }

    /**
     * @param int $user_from - идентификатор пользователя который отслеживает статьи
     * @param int $user_to - идентификатор пользователя на которого подписан
     * @return Follows
     */
    public function followCheck(int $user_from, int $user_to){
        return Follows::where('from_user_id', '=', $user_from)->where('to_user_id', '=', $user_to)->first();
    }

    /**
     * Считает количество подписчиков
     * @param $user_id - идентификатор пользователя
     * @return mixed
     */
    public function countFollowToByUser($user_id){
        return $this->startConditions()->where('to_user_id', $user_id)->get()->count();
    }

    /**
     * Считает количество подписок
     * @param $user_id - идентификатор пользователя
     * @return mixed
     */
    public function countFollowFromByUser($user_id){
        return $this->startConditions()->where('from_user_id', $user_id)->get()->count();
    }

    /**
     * Получить модель для редактирования
     * @param $id
     * @return mixed
     */
    public function getEdit(int $id){
        return $this->startConditions()->find($id);
    }
}