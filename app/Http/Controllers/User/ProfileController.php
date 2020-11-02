<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\UserUpdateReques;
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
        $user = $this->userRepository->getUser(\Auth::id());
        $userSexList = $this->userRepository->getSexUserList();
        $follow_count = $this->followRepository->countFollowToByUser(\Auth::id());
        $follow_from_count = $this->followRepository->countFollowFromByUser(\Auth::id());

        $title = 'Мой профиль';

        return view('user.profile.profile', compact(
            'user',
            'userSexList',
            'follow_count',
            'follow_from_count',
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
        $id = (int)$id;
        //
        //$roles = Role::orderBy('id', 'ASC')->get();
        $user_item = $this->userRepository->getUser($id);
        $user_posts = $this->postRepository->getPreviewPostsListByUser($id, \Auth::id(), 10);
        $follow_count = $this->followRepository->countFollowToByUser($id);
        $follow_from_count = $this->followRepository->countFollowFromByUser($id);

        $follow_check = [];
        if(\Auth::check()){
            $follow_check = $this->followRepository->followCheck(\Auth::user()->id, $id);
        }
        $tags_to_post = [];
        if(!empty($user_posts)){
            $tags_to_post = $this->tagRepository->getTagsToPosts($user_posts->collect()->all());
        }

        $title = 'Профиль пользователя ' . $user_item->name;

        return view('user.profile.show', compact(
            'user_posts',
            'user_item',
            'follow_check',
            'tags_to_post',
            'follow_count',
            'follow_from_count',
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserUpdateReques $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateReques $request, $id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserUpdateReques $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UserUpdateReques $request)
    {
        //

        $id = \Auth::id();
        $data = $request->all();
        $user = $this->userRepository->getEdit($id);
        /*
        if(empty($data['slug'])){
            $data['slug'] = Str::slug($data['title']);
        }
        */
        // В Model/BlogCategory не обходимо указать поля, которым разрешено массовое присвоение
        //$result = $item->fill($data)->save();
        $result = $user->update($data);
        if(!$result){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении данных. Попробуйте позже."]);
        }
        return response()->json(["success" => "Y", "message" => "Данные успешно сохранены"]);
    }

    /**
     * Загружает аватар пользователя
     *
     * @param  App\Http\Requests $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request)
    {
        $id = \Auth::id();
        $user = $this->userRepository->getEdit($id);

        $path = 'uploads/users/' . \Auth::id() . '/avatar';

        $result = $request->file('userAvatar')->store($path, 'public');

        \Storage::delete('public/'.$user->getOriginal()['avatar']);

        $result_update = $user->update(['avatar' => $result]);

        if(!$result_update){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении аватара. Попробуйте позже."]);
        }
        return response()->json(["success" => "Y", "message" => "Данные успешно сохранены", "url" => asset('/storage/' . $result)]);
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
}
