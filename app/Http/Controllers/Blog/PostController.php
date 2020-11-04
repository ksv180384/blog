<?php

namespace App\Http\Controllers\Blog;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Blog\Follows;
use App\Models\Blog\Like;
use App\Models\Blog\Post;
use App\Models\Blog\PostToTag;
use App\Models\Blog\Tag;
use App\Repositories\CommentRepository;
use App\Repositories\FollowRepository;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    const IMG_PATH = 'uploads/posts';

    private $postRepository;
    private $followRepository;
    private $likeRepository;
    private $tagRepository;
    private $commentRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth')->except(['show', 'postsByTag']);

        $this->postRepository = app(PostRepository::class);
        $this->followRepository = app(FollowRepository::class);
        $this->likeRepository = app(LikeRepository::class);
        $this->tagRepository = app(TagRepository::class);
        $this->commentRepository = app(CommentRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function my()
    {
        //
        $posts = $this->postRepository->getPreviewPostsListByUser(\Auth::user()->id, \Auth::user()->id,10);
        $tags_to_post = [];
        if(!empty($posts)){
            $tags_to_post = $this->tagRepository->getTagsToPosts($posts->collect()->all());
        }

        $title = 'Мои посты';

        return view('blog.post.my_posts', compact(
            'posts',
            'tags_to_post',
            'title'
        ));
    }

    public function postsByTag($tag_id){

        $user_id = \Auth::check() ? \Auth::id() : 0;
        $posts = $this->postRepository->getPostsListByTag($tag_id, $user_id, 10);
        $tag = Tag::where('id', '=', $tag_id)->first();

        $tags_to_post = [];
        if(!empty($posts)){
            $tags_to_post = $this->tagRepository->getTagsToPosts($posts->collect()->all());
        }

        $title = 'Посты тега ' . $tag->title;

        return view('blog.post.tag_posts', compact(
            'posts',
            'tags_to_post',
            'tag',
            'title'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tags_list = Tag::all();

        $title = 'Новый пост';

        return view('blog.post.create', compact(
            'tags_list',
            'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(PostCreateRequest $request)
    {
        //
        if(\Auth::user()->id != $request->user_id){
            return response()->json(["success" => "N", "message" => "Неверно задан пользователь"]);
        }

        $img_path = null;
        if($request->file('img')){
            if(!$img_path = $request->file('img')->store(self::IMG_PATH, 'public')){
                return response()->json(["success" => "N", "message" => "Ошибка при записи изображения"]);
            }
        }

        \DB::transaction(function() use ($img_path, $request){
            $Post = Post::create([
                'user_id' => $request->input('user_id'),
                'img' => $img_path,
                'title' => $request->input('title'),
                'excerpt' => $request->input('excerpt'),
                'content' => $request->input('content'),
            ]);

            if($request->tags){
                $tagsToPostList = [];
                foreach ($request->tags as $tag){
                    $tagsToPostList[] = [
                        'post_id' => $Post->id,
                        'tag_id' => $tag,
                    ];
                }
                PostToTag::insert($tagsToPostList);
            }

        });

        return response()->json(["success" => "Y", "message" => "Данные успешно сохранены", "redirect" => route('post.my')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        $id = (int)$id;
        $post = $this->postRepository->getPost($id);
        $tags = $this->postRepository->getTagsToPost($id) ?: [];
        $count_like = $this->likeRepository->countByPost($id);
        $like = \Auth::check() ? $this->likeRepository->getLikeToPostAndUser($post->id, \Auth::id()) : false;
        $comments = $this->commentRepository->getCommentsByPost($post->id);
        $comments_count = $this->commentRepository->countByPost($post->id);

        $title = $post->title;

        return view('blog.post.show', compact(
            'post',
            'tags',
            'count_like',
            'like',
            'comments',
            'comments_count',
            'title'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = $this->postRepository->getPost($id);

        if($post->user_id != \Auth::id()){
            abort(404, 'У вас недостаточно прав.');
        }

        $tags_list = Tag::all();
        $tags_to_post_collection = $this->postRepository->getTagsToPost($id) ?: [];
        $tags_to_post = [];

        foreach ($tags_to_post_collection as $el){
            $tags_to_post[] = $el->id;
        }

        $title = 'Редактировать пост ' . $post->title;

        return view('blog.post.edit', compact(
            'post',
            'tags_list',
            'tags_to_post',
            'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PostUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(PostUpdateRequest $request, $id)
    {
        //

        $Post = $this->postRepository->getEdit($id);
        if($Post->user_id != \Auth::user()->id){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав."]);
        }

        $post_update = [
            'title' => $request->input('title'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
        ];

        $img_path = null;
        if($request->file('img')){
            if(!$img_path = $request->file('img')->store(self::IMG_PATH, 'public')){
                return response()->json(["success" => "N", "message" => "Ошибка при записи изображения"]);
            }
        }
        if($img_path){
            \Storage::delete('public/' . $Post->getOriginal()['img']);
            $post_update['img'] = $img_path;
        }


        \DB::transaction(function() use ($Post, $post_update, $request, $id) {

            $result = $Post->update($post_update);

            // Удаляем теги привязанные к посту
            PostToTag::deleteTagsByPost($Post->id);

            if ($request->tags) {
                $tagsToPostList = [];
                foreach ($request->tags as $tag) {
                    $tagsToPostList[] = [
                        'post_id' => $Post->id,
                        'tag_id' => $tag,
                    ];
                }
                PostToTag::insert($tagsToPostList);
            }
        });

        return response()->json(["success" => "Y", "message" => "Пост успешно отредактирован."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Добавляет лайк к посту
     * @param $id - идентификатор поста
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLike($id){
        $id = (int)$id;
        if(Like::checkLike($id, \Auth::id())){
            return response()->json(["success" => "N", "message" => "Вы уже ставили лайк этому посту."]);
        }

        $Like = Like::create([
            'post_id' => $id,
            'user_id' => \Auth::id(),
        ]);

        if(!$Like){
            return response()->json(["success" => "N", "message" => "Ошибка. Попробуйте позже."]);
        }

        // Считаем лайки поста
        $count = $this->likeRepository->countByPost($id);

        return response()->json([
            "success" => "Y",
            "message" => "",
            "type" => "add",
            "href" => route('post.like-remove', $Like->id),
            "count" => $count,
        ]);
    }

    /**
     * Добавляет лайк к посту
     * @param $id - идентификатор лайка
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeLike($id){
        $id = (int)$id;

        $Like = Like::where('id', '=', $id)->first();
        if(!$Like){
            return response()->json(["success" => "N", "message" => "Ошибка. Попробуйте позже."]);
        }

        if($Like->user_id != \Auth::id()){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав."]);
        }

        $post_id = $Like->post_id;
        if(!$Like->delete()){
            return response()->json(["success" => "N", "message" => "Ошибка. Попробуйте позже."]);
        }

        // Считаем лайки поста
        $count = $this->likeRepository->countByPost($Like->post_id);

        return response()->json([
            "success" => "Y",
            "message" => "",
            "type" => "remove",
            "href" => route('post.like-add', $post_id),
            "count" => $count,
        ]);
    }
}
