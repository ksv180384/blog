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

        $Comment = new Comment();
        $Comment->user_id = $request->user_id;
        $Comment->post_id = $request->post_id;
        $Comment->comment = $request->comment;
        if(!$Comment->save()){
            return response()->json(["success" => "N", "message" => "Ошибка при добавлении комментария."]);
        }

        $comments_count = $this->commentRepository->countByPost($Comment->post_id);
        $comment = $this->commentRepository->getComment($Comment->id);

        return response()->json([
            "success" => "Y",
            "message" => "Комментарий успешно добавлен.",
            "html" => view('blog.post.comment_item', compact('comment'))->render(),
            "count_messages" => $comments_count,
            ]);
    }
}
