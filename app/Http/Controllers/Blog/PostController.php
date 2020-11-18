<?php

namespace App\Http\Controllers\Blog;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Blog\Like;
use App\Models\Blog\Post;
use App\Models\Blog\PostToTag;
use App\Models\Blog\Tag;
use App\Repositories\CommentRepository;
use App\Repositories\FollowRepository;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;


class PostController extends BaseController
{
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
        $posts = $this->postRepository->getPreviewPostsListByUserPublishedAll(\Auth::user()->id, 10);

        $title = 'Мои посты';

        return view('blog.post.my_posts', compact(
            'posts',
            'tags_to_post',
            'title'
        ));
    }

    /**
     * Посты по тегу
     * @param int $tag_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postsByTag($tag_id){

        $tag = Tag::findOrFail($tag_id);
        $posts = $this->postRepository->getPostsListByTag($tag_id, 10);

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
        $img_path = null;
        if($request->file('img')){
            if(!$img_path = request()->file('img')->store('posts')){
                return response()->json(['message' => 'Ошибка при записи изображения'], 404);
            }
        }

        try{
            \DB::transaction(function() use ($img_path, $request){
                $Post = Post::create([
                    'user_id' => \Auth::id(),
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
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Ошибка. Попробуйте позже.'], 404);
        }

        return response()->json([
            'message' => 'Данные успешно сохранены', '
            redirect' => route('post.my')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $like = \Auth::check() ? $this->likeRepository->getLikeToPostAndUser($post->id, \Auth::id()) : false;
        $title = $post->title;
        $comments = $this->commentRepository->getCommentsByPost($post->id);

        return view('blog.post.show', compact(
            'post',
            'comments',
            'like',
            'title'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        if($post->user_id != \Auth::id()){
            abort(404, 'У вас недостаточно прав.');
        }

        $tags_list = Tag::all();

        $title = 'Редактировать пост ' . $post->title;

        return view('blog.post.edit', compact(
            'post',
            'tags_list',
            'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PostUpdateRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        //
        if($post->user_id != \Auth::id()){
            return response()->json(['message' => 'У вас недостаточно прав.'], 404);
        }

        $img_path = null;
        if($request->has('img')){
            $img_path = request()
                ->file('img')
                ->store('posts');
        }
        if($img_path){
            \Storage::delete($post->img);
        }else{
            $post->img = $img_path;
        }

        $post_update = [
            'title' => $request->input('title'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'img' => $img_path,
        ];

        try {
            \DB::transaction(function() use ($post, $post_update, $request) {

                $post->update($post_update);

                // Удаляем теги привязанные к посту
                PostToTag::deleteTagsByPost($post->id);

                if ($request->tags) {
                    $tagsToPostList = [];
                    foreach ($request->tags as $tag) {
                        $tagsToPostList[] = [
                            'post_id' => $post->id,
                            'tag_id' => $tag,
                        ];
                    }
                    PostToTag::insert($tagsToPostList);
                }
            });
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Ошибка. Попробуйте позже.'], 404);

        }

        return response()->json(['message' => 'Пост успешно отредактирован.']);
    }

    /**
     * Добавляет лайк к посту
     * @param int $id - идентификатор поста
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLike($id){
        $id = (int)$id;
        if(Like::checkLike($id, \Auth::id())){
            return response()->json(['message' => 'Вы уже ставили лайк этому посту'], 404);
        }

        $Like = Like::create([
            'post_id' => $id,
            'user_id' => \Auth::id(),
        ]);

        if(!$Like){
            return response()->json(['message' => 'Ошибка. Попробуйте позже'], 404);
        }

        // Считаем лайки поста
        $count = $this->likeRepository->countByPost($id);

        return response()->json([
            'message' => '',
            'type' => 'add',
            'count' => $count,
        ]);
    }

    /**
     * Добавляет лайк к посту
     * @param int $post_id - идентификатор поста
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeLike($post_id){
        $post_id = (int)$post_id;

        if(!\Auth::check()){
            return response()->json(['message' => 'Вы не авторизованы'], 404);
        }

        $like = Like::where('post_id', '=', $post_id)->where('user_id', '=', \Auth::id())->first();
        if(!$like){
            return response()->json(['message' => 'Ошибка. Невозможно удалить лайк'], 404);
        }

        if(!$like->delete()){
            return response()->json(['message' => 'Ошибка. Попробуйте позже']);
        }

        // Считаем лайки поста
        $count = $this->likeRepository->countByPost($like->post_id);

        return response()->json([
            'message' => '',
            'type' => 'remove',
            'count' => $count,
        ]);
    }
}
