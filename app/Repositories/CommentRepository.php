<?php


namespace App\Repositories;


use App\Models\Blog\Comment as Model;
use App\Models\Blog\Comment;
use App\Models\User\User;


class CommentRepository extends CoreRepository
{
    public function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем комментарии поста
     * @param int $post_id - идентификатор поста
     * @param int $paginate - колличество постов на странице
     * @return mixed
     */
    public function getCommentsByPost(int $post_id, $paginate = 10){
        $columns = [
            'comments.id',
            'comments.user_id',
            'comments.comment',
            'comments.created_at',
            'comments.updated_at',
            'users.name',
            'users.avatar',
            'users.avatar_select',
        ];

        $comments = Comment::select($columns)
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('post_id', '=', $post_id)->paginate($paginate);

        foreach ($comments as $k => $comment){
            $comments[$k]->avatar = User::activeAvatar($comment->avatar);
        }
        return $comments;
    }

    /**
     * Получаем комментарий
     * @param int $id - идентификатор комментария
     * @return mixed
     */
    public function getComment(int $id){
        $columns = [
            'comments.id',
            'comments.user_id',
            'comments.comment',
            'comments.created_at',
            'comments.updated_at',
            'users.name',
            'users.avatar',
            'users.avatar_select',
        ];

        $comment = Comment::select($columns)
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('comments.id', '=', $id)->first();

        $comment->avatar = User::activeAvatar($comment->avatar);
        return $comment;
    }

    /**
     * Считает количество лайков поста
     * @param $post_id - идентификатор поста
     * @return mixed
     */
    public function countByPost(int $post_id){
        return $this->startConditions()->where('post_id', $post_id)->get()->count();
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