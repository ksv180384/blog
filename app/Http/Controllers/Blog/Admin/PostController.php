<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Blog\Post;
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

        return view('blog.post.adm.posts', compact(
            'posts',
            'tags'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        if(!\Auth::user()->can('blog-posts-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        $count_like = $this->likeRepository->countByPost($post->id);
        $like = \Auth::check() ? $this->likeRepository->getLikeToPostAndUser($post->id, \Auth::id()) : false;
        $comments = $this->commentRepository->getCommentsByPost($post->id);
        $comments_count = $this->commentRepository->countByPost($post->id);

        $title = $post->title;

        return view('blog.post.adm.show', compact(
            'post',
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
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        if(!\Auth::user()->can('blog-posts-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        //$post = Post::find($id);
        $tags_list = Tag::all();

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
     * @param  Post $post
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        //
        if(!\Auth::user()->can('blog-posts-edit')){
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

    public function published(int $id){
        if(!\Auth::user()->can('blog-posts-published')){
            return response()->json([
                'message' => 'У вас недостаточно прав для публикации постов.',
            ], 404);
        }
        $post = $this->postRepository->getEdit($id);
        if(!$post){
            return response()->json([
                'message' => 'Неверно задан пост.',
            ], 404);
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
                'message' => 'Ошибка при попытке публикации поста',
            ]);
        }

        $date = '<span class="text-danger">неопубликованно</span>';
        if($post->published_at){
            $date = '<strong>' . $post->published_at->format('H:i') . '</strong>' . $post->published_at->format('d.m.Y');
        }

        return response()->json([
            'message' => $message,
            'text' => $text,
            'classCss' => $class_css,
            'date' => $date,
        ]);
    }
}
