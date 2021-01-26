<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\UserAvatarUpdateRequest;
use App\Http\Requests\UserUpdateReques;
use App\Models\User\User;
use App\Models\User\UserSex;
use App\Repositories\FollowRepository;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ProfileController extends BaseController
{
    private $userRepository;
    private $postRepository;
    private $followRepository;
    private $tagRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth')->except('show');

        $this->userRepository = app(UserRepository::class);
        $this->postRepository = app(PostRepository::class);
        $this->followRepository = app(FollowRepository::class);
        $this->tagRepository = app(TagRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userSexList = UserSex::all();
        $user = \Auth::user();

        $title = 'Мой профиль';

        return view('user.profile.profile', compact(
            'user',
            'userSexList',
            'title'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_item = User::findOrFail($id);
        $posts = $user_item->posts()->with(['user', 'tags', 'commentsCount', 'likesCount', 'checkUserLike'])->paginate(10);

        $follow_check = [];
        if(\Auth::check()){
            // Проверяем подписаны ли мы на пользователя
            $follow_check = $this->followRepository->followCheck(\Auth::id(), $user_item->id);
        }

        $title = 'Профиль пользователя ' . $user_item->name;

        return view('user.profile.show', compact(
            'posts',
            'user_item',
            'follow_check',
            'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateReques $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateReques $request, $id)
    {
        $data = $request->all();
        $user = \Auth::user();

        $result = $user->update($data);
        if(!$result){
            return response()->json(['message' => 'Ошибка при сохранении данных. Попробуйте позже.'], 404);
        }
        return response()->json(['message' => 'Данные успешно сохранены']);

    }

    /**
     * Загружает аватар пользователя
     *
     * @param  UserAvatarUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(UserAvatarUpdateRequest $request)
    {

        $user = \Auth::user();

        $path = 'users/' . \Auth::id() . '/avatar';

        $result = $request->file('avatar')->store($path, 'public');

        \Storage::delete($user->getOriginal()['avatar']);

        $result_update = $user->update(['avatar' => $result]);

        if(!$result_update){
            return response()->json(['message' => 'Ошибка при сохранении аватара. Попробуйте позже.'], 404);
        }
        return response()->json(['message' => 'Данные успешно сохранены', 'url' => \Storage::url($result)]);
    }
}
