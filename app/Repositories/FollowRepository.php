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
    public function getFollowsPostsByUser(int $user_id, int $paginate = 10){

        $posts = Post::with(['user', 'tags', 'checkUserLike'])
            ->withCount(['comments', 'likes'])
            ->join('follows', 'posts.user_id', '=', 'follows.to_user_id')
            ->where('follows.from_user_id', '=', $user_id)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate($paginate);

        return $posts;
    }

    /**
     * @param int $user_from - идентификатор пользователя который отслеживает статьи
     * @param int $user_to - идентификатор пользователя на которого подписан
     * @return Follows
     */
    public function followCheck(int $user_from, int $user_to){
        return Follows::where('from_user_id', '=', $user_from)->where('to_user_id', '=', $user_to)->first();
    }
}