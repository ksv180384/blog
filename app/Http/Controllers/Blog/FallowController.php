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
        $posts = $this->followRepository->getFollowsPostsByUser(\Auth::id(), 10);
        $user = \Auth::user();

        $title = 'Подписан';

        return view('blog.fallow_list', compact(
            'posts',
            'user',
            'title'
        ));
    }

    /**
     * Подписываемся
     * @param FollowRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function add(FollowRequest $request){

        if ($request->to_user_id == \Auth::user()->id){
            return response()->json(['message' => 'Вы не можете отслеживать свои посты.'], 404);
        }

        $Follow = Follows::create([
            'from_user_id' => $request->from_user_id,
            'to_user_id' => $request->to_user_id,
        ]);

        if(!$Follow){
            return response()->json(['message' => 'Ошибка. Попробуйте позже.'], 404);
        }

        $User = User::find($request->to_user_id);

        $follow_check = $Follow;
        return response()->json([
            'message' => 'Теперь вы отслеживаете посты пользователя ' . $User->name . '.',
            'follow_count' => $User->followToCount,
            'html' => view('user.profile.btn.btn_follow_remove', compact( 'follow_check'))->render(),
        ]);
    }

    /**
     * Отписываемся от пользователя
     * @param int $to_user_id - идентификатор пользователя на которого подписаны
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy($to_user_id){
        $Follow = Follows::where('to_user_id', $to_user_id)->where('from_user_id', \Auth::id());

        // Проверяем подписку, принадлежит ли она текущему пользователю
        if(!$Follow){
            return response()->json(['message' => 'Неверный пользователь.'], 404);
        }
        // Удаляем связь отслеживания постов пользователя
        $Follow->delete();

        $user_item = User::find($to_user_id);

        return response()->json([
            'message' => 'Вы больше не отслеживаете посты пользователя ' . $user_item->name . '.',
            'follow_count' => $user_item->followToCount,
            'html' => view('user.profile.btn.btn_follow_add', compact('user_item'))->render(),
        ]);
    }
}
