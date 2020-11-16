<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCommentRequest;
use App\Models\Blog\Comment;
use App\Repositories\CommentRepository;

class CommentController extends BaseController
{
    private $commentRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->commentRepository = app(CommentRepository::class);
    }

    //
    public function store(BlogCommentRequest $request){

        //$Comment = new Comment();
        if(!$comment = Comment::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'comment' => $request->comment,
        ])){
            return response()->json(['message' => 'Ошибка при добавлении комментария.'], 404);
        }

        $comments_count = $this->commentRepository->countByPost($comment->post_id);

        return response()->json([
            'message' => 'Комментарий успешно добавлен.',
            'html' => view('blog.post.comment_item', compact('comment'))->render(),
            'count_messages' => $comments_count,
            ]);
    }
}
