<?php

namespace App\Http\Controllers;

use App\Models\Blog\Tag;
use App\Repositories\PostRepository;


class IndexController extends Controller
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->postRepository = app(PostRepository::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $posts = $this->postRepository->getPreviewPostsList(10);
        $tags = Tag::all(['id', 'title']);
        $title = 'Главная';

        return view('index', compact(
            'posts',
            'tags',
            'title'
        ));
    }
}