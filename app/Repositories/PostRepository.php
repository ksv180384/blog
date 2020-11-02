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
            'user_sex.title AS sex_title',
            'user_status.title AS status_title',
            'roles.name AS role_name',
        ];

        $post = $this->startConditions()
            ->select($columns)
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('posts.id', '=', $id)
            //->toBase()
            ->first();

        $post->avatar = User::activeAvatar($post->avatar);
        return $post;
    }

    /**
     * Получает список постов для страницы со списком постов. Не включет в себя полную статью (content)
     * @param int $paginate - количествл получаемых записей
     * @param int $user_id - идентификатор пользователя
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewPostsList($paginate = 5, $user_id = 0){
        $columns = [
            'posts.id',
            'posts.user_id',
            'posts.img',
            'posts.title',
            'posts.excerpt',
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
            'user_sex.title AS sex_title',
            'user_status.title AS status_title',
            'roles.name AS role_name',
            \DB::raw('(SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS likes_count'),
            \DB::raw('(SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comments_count'),
        ];
        if($user_id){
            $columns[] = 'likes.id AS check_like';
        }
        $posts = $this->startConditions()
            ->select($columns)
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
        if($user_id){
            $posts = $posts->leftJoin('likes', function($join) use ($user_id){
                $join->on('likes.post_id', '=', 'posts.id')->where('likes.user_id', '=', $user_id);
                //$join->on('likes.user_id', '=', 'users.id')
            });
        }
        $posts = $posts->whereNotNull('posts.published_at')
                        ->orderBy('posts.published_at', 'DESC')
                        ->paginate($paginate);

        foreach ($posts as $k => $post){
            $posts[$k]->avatar = User::activeAvatar($post->avatar);
        }

        return $posts;
    }

    /**
     * Получает список постов для страницы со списком постов. Не включет в себя полную статью (content)
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewPostsListAdm($paginate = 5){
        $columns = [
            'posts.id',
            'posts.user_id',
            'posts.img',
            'posts.title',
            'posts.excerpt',
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
            'user_sex.title AS sex_title',
            'user_status.title AS status_title',
            'roles.name AS role_name',
            \DB::raw('(SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS likes_count'),
            \DB::raw('(SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comments_count'),
        ];
        $posts = $this->startConditions()
            ->select($columns)
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->orderBy('posts.created_at', 'DESC')
            ->paginate($paginate);

        foreach ($posts as $k => $post){
            $posts[$k]->avatar = User::activeAvatar($post->avatar);
        }

        return $posts;
    }

    /**
     * Получает список постов определенного ползователя для страницы со списком постов. Не включет в себя полную статью (content)
     * @param int $user_id - идентификатор пользователя
     * @param int $user_id_like - идентификатор пользователя, который просматривает страницу для проверки ставил ли он уже лайк
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPreviewPostsListByUser($user_id, $user_id_like, $paginate = 5){
        $columns = [
            'posts.id',
            'posts.user_id',
            'posts.img',
            'posts.title',
            'posts.excerpt',
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
            'user_sex.title AS sex_title',
            'user_status.title AS status_title',
            'roles.name AS role_name',
            \DB::raw('(SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS likes_count'),
            \DB::raw('(SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comments_count'),
            'likes.id AS check_like',
        ];
        $posts = $this->startConditions()
            ->select($columns)
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('likes', function($join) use ($user_id_like){
                $join->on('likes.post_id', '=', 'posts.id')->where('likes.user_id', '=', $user_id_like);
            })
            ->where('posts.user_id', '=', $user_id)
            ->orderBy('posts.created_at', 'DESC')
            ->paginate($paginate);

        foreach ($posts as $k => $post){
            $posts[$k]->avatar = User::activeAvatar($post->avatar);
        }

        return $posts;
    }

    /**
     * Получает список постов
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPostsList($paginate = 5){
        $columns = [
            'posts.id',
            'posts.user_id',
            'posts.title',
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
            'user_sex.title AS sex_title',
            'user_status.title AS status_title',
            'roles.name AS role_name',
        ];
        $posts = $this->startConditions()
            ->select($columns)
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->orderBy('posts.published_at', 'DESC')
            ->paginate($paginate);

        foreach ($posts as $k => $post){
            $posts[$k]->avatar = User::activeAvatar($post->avatar);
        }

        return $posts;
    }

    /**
     * Получает список тегов поста
     * @param int $post_id - идентификатор поста
     * @return \Illuminate\Support\Collection
     */
    public function getTagsToPost($post_id){
        $columns = [
            'tags.id',
            'tags.user_id',
            'tags.title',
            'tags.active',
            'tags.created_at',
            'tags.updated_at'
        ];
        $tags = PostToTag::select($columns)
            ->join('tags', 'tags.id', '=', 'post_to_tag.tag_id')
            ->where('post_id', '=', $post_id)
            ->get();

        return $tags;
    }

    /**
     * Получает список постов прикрепленных к определенному тегу
     * @param int $tag_id - идентификатор тега
     * @param int $user_id - идентификатор пользователя
     * @param int $paginate - количествл получаемых записей
     * @return \Illuminate\Support\Collection
     */
    public function getPostsListByTag($tag_id, $user_id = 0, $paginate = 5){
        $columns = [
            'posts.id',
            'posts.user_id',
            'posts.img',
            'posts.title',
            'posts.excerpt',
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
            'user_sex.title AS sex_title',
            'user_status.title AS status_title',
            'roles.name AS role_name',
            \DB::raw('(SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS likes_count'),
            \DB::raw('(SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comments_count'),
        ];
        if($user_id){
            $columns[] = 'likes.id AS check_like';
        }
        $posts = $this->startConditions()
            ->select($columns)
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('post_to_tag', 'post_to_tag.post_id', '=', 'posts.id');
        if($user_id){
            $posts = $posts->leftJoin('likes', function($join) use ($user_id){
                $join->on('likes.post_id', '=', 'posts.id')->where('likes.user_id', '=', $user_id);
            });
        }
        $posts = $posts->where('post_to_tag.tag_id', '=', $tag_id)
            ->orderBy('posts.published_at', 'DESC')
            ->paginate($paginate);

        foreach ($posts as $k => $post){
            $posts[$k]->avatar = User::activeAvatar($post->avatar);
        }

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