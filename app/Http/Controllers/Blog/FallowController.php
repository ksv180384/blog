<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowRequest;
use App\Models\Blog\Follows;
use App\Models\User\User;
use App\Repositories\FollowRepository;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;

class FallowController extends BaseController
{
    private $followRepository;
    private $tagRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->followRepository = app(FollowRepository::class);
        $this->tagRepository = app(TagRepository::class);
    }

    /**
     * Выводит список статей на которые подписан пользователь.
     *
     * @return array \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = $this->followRepository->getFollowsPostsByUser(\Auth::user()->id, 10) ?: [];
        $follows_list = $this->followRepository->getFollowsByUser(\Auth::user()->id) ?: [];

        $title = 'Подписан';

        return view('blog.fallow_list', compact(
            'posts',
            'follows_list',
            'tags_to_post',
            'title'
        ));
    }

    /**
     * @param FollowRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function add(FollowRequest $request){

        if ($request->to_user_id == \Auth::user()->id){
            return response()->json(["success" => "N", "message" => "Вы не можете отслеживать свои посты."]);
        }

        $Follow = Follows::create([
            'from_user_id' => $request->from_user_id,
            'to_user_id' => $request->to_user_id,
        ]);

        if(!$Follow){
            return response()->json(["success" => "N", "message" => "Ошибка. Попробуйте позже."]);
        }

        $User = User::where('id', '=', $request->to_user_id)->first();
        $follow_count = $this->followRepository->countFollowToByUser($request->to_user_id);

        $follow_check = $Follow;
        return response()->json([
            "success" => "Y",
            "message" => "Теперь вы отслеживаете посты пользователя " . $User->name . ".",
            "follow_count" => $follow_count,
            "html" => view('user.profile.btn.btn_follow_remove', compact( 'follow_check'))->render(),
        ]);
    }

    /**
     * @param $id - идентификатор подписки
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy($id){
        $id = (int)$id;
        $Follow = Follows::where('id', '=', $id)->first();

        // Проверяем подписку, принадлежит ли она текущему пользователю
        if($Follow->from_user_id != \Auth::user()->id){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав."]);
        }
        $User = User::where('id', '=', $Follow->to_user_id)->first();
        $from_user_id = $Follow->from_user_id;
        // Удаляем связь отслеживания постов пользователя
        $Follow->delete();
        $follow_count = $this->followRepository->countFollowToByUser($from_user_id);

        $user_item = $User;
        return response()->json([
            "success" => "Y",
            "message" => "Вы больше не отслеживаете посты пользователя " . $User->name . ".",
            "follow_count" => $follow_count,
            "html" => view('user.profile.btn.btn_follow_add', compact('user_item'))->render(),
        ]);
    }
}
