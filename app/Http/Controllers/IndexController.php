<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use App\Models\User\User;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $postRepository;
    private $tagRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->postRepository = app(PostRepository::class);
        $this->tagRepository = app(TagRepository::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = $this->postRepository->getPreviewPostsList(
            10,
            \Auth::check() ? \Auth::id() : null
        );
        $tags = Tag::all(['id', 'title']);
        $tags_to_post = $this->tagRepository->getTagsToPosts($posts->collect()->all());

        $title = 'Главная';

        return view('index', compact(
            'posts',
            'tags',
            'tags_to_post',
            'title'
        ));
    }
}