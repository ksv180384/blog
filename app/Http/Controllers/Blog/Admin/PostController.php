<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Blog\PostToTag;
use App\Models\Blog\Tag;
use App\Repositories\CommentRepository;
use App\Repositories\FollowRepository;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends BaseController
{

    private $postRepository;
    private $followRepository;
    private $tagRepository;
    private $likeRepository;
    private $commentRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->postRepository = app(PostRepository::class);
        $this->followRepository = app(FollowRepository::class);
        $this->tagRepository = app(TagRepository::class);
        $this->likeRepository = app(LikeRepository::class);
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
        if(!\Auth::user()->can('blog-posts-edit')){
            abort(404, 'У вас недостаточно прав.');
        }
        $posts = $this->postRepository->getPreviewPostsListAdm(10);
        $tags = Tag::all(['id', 'title']);
        $tags_to_post = [];
        if(!empty($posts)){
            $tags_to_post = $this->tagRepository->getTagsToPosts($posts->collect()->all());
        }

        return view('blog.post.adm.posts', compact(
            'posts',
            'tags_to_post',
            'tags'
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if(!\Auth::user()->can('blog-posts-edit')){
            abort(404, 'У вас недостаточно прав.');
        }
        $id = (int)$id;
        $post = $this->postRepository->getPost($id);
        $tags = $this->postRepository->getTagsToPost($id) ?: [];
        $count_like = $this->likeRepository->countByPost($id);
        $like = \Auth::check() ? $this->likeRepository->getLikeToPostAndUser($post->id, \Auth::id()) : false;
        $comments = $this->commentRepository->getCommentsByPost($post->id);
        $comments_count = $this->commentRepository->countByPost($post->id);

        $title = $post->title;

        return view('blog.post.adm.show', compact(
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

        if(!\Auth::user()->can('blog-posts-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        $tags_list = Tag::all();
        $tags_to_post_collection = $this->postRepository->getTagsToPost($id) ?: [];
        $tags_to_post = [];

        foreach ($tags_to_post_collection as $el){
            $tags_to_post[] = $el->id;
        }

        $title = $post->title;

        return view('blog.post.adm.edit', compact(
            'post',
            'tags_list',
            'tags_to_post',
            'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(PostUpdateRequest $request, $id)
    {
        //
        $Post = $this->postRepository->getEdit($id);
        if(!\Auth::user()->can('blog-posts-edit')){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав."]);
        }

        $post_update = [
            'title' => $request->input('title'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
        ];

        $img_path = null;
        if($request->file('img')){
            $img_path = $request
                            ->file('img')
                            ->store(\App\Http\Controllers\Blog\PostController::IMG_PATH, 'public');
            if(!$img_path){
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

    public function published(int $id){
        if(!\Auth::user()->can('blog-posts-published')){
            return response()->json([
                "success" => "N",
                "message" => "У вас недостаточно прав для публикации постов.",
            ]);
        }
        $post = $this->postRepository->getEdit($id);
        if(!$post){
            return response()->json([
                "success" => "N",
                "message" => "Неверно задан пост.",
            ]);
        }

        if($post->published_at){
            $post->published_at = null;
            $text = 'Опубликовать';
            $class_css = 'btn-primary';
            $message = 'Пост успешно снят с публикации.';
        }else{
            $post->published_at = Carbon::now();
            $text = 'Снять с публикации';
            $class_css = 'btn-default';
            $message = 'Пост успешно опубликован.';
        }
        if(!$post->save()){
            return response()->json([
                "success" => "N",
                "message" => "Ошибка при попытке публикации поста",
            ]);
        }

        $date = '<span class="text-danger">неопубликованно</span>';
        if($post->published_at){
            $date = '<strong>'.
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$post->published_at)->format('H:i').'
                    </strong>'.
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$post->published_at)->format('d.m.Y');
        }

        return response()->json([
            "success" => "Y",
            "message" => $message,
            "text" => $text,
            "classCss" => $class_css,
            "date" => $date,
        ]);
    }
}
