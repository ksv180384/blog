<?php


namespace App\Repositories;


use App\Models\Blog\Like as Model;


class LikeRepository extends CoreRepository
{
    public function getModelClass()
    {
        return Model::class;
    }

    /**
     * Считает количество лайков поста
     * @param $post_id - идентификатор поста
     * @return mixed
     */
    public function countByPost($post_id){
        return $this->startConditions()->where('post_id', $post_id)->get()->count();
    }

    /**
     * Проверяет сьавил ли пользователь лайк посту
     * @param int $post_id - идентификатор поста
     * @param int $user_id - идентификатор пользователя
     * @return bool
     */
    public function getLikeToPostAndUser(int $post_id, int $user_id = 0){
        return $this->startConditions()->where('post_id', '=', $post_id)->where('user_id', '=', $user_id)->first();
    }
}