<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateReques;
use App\Models\User\User;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    const COUNT_USERS_LIST = 10;

    private $userRepository;
    private $postRepository;
    private $tagRepository;

    public function __construct()
    {
        parent::__construct();

        $this->userRepository = app(UserRepository::class);
        $this->postRepository = app(PostRepository::class);
        $this->tagRepository = app(TagRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('user-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $usersList = $this->userRepository->getUsersList(self::COUNT_USERS_LIST);

        return view('user.list', compact('usersList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('user-create')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id', 'ASC')->get();
        $sexList = $this->userRepository->getSexUserList();

        return view('user.create', compact('sexList', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('user-create')){
            abort(404, 'У вас недостаточно прав.');
        }
        if($request->input('password') != $request->input('password_confirmation')){
            return response()->json(["success" => "N", "message" => "Неверно подтвержден пароль."]);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['email_verified_at'] = \Carbon\Carbon::now();

        if(!$User = User::create($data)){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении данных. Попробуйте позже."]);
        }

        if(!$role = Role::where('id', '=', $data['user_role'])->first()){
            return response()->json(["success" => "N", "message" => "Ошибка при получении роли. Попробуйте позже."]);
        }

        // Удаляем текущие роли пользователя и присваеваем новые
        if(!$User->syncRoles($role->name)){
            return response()->json(["success" => "N", "message" => "Ошибка при изменении роли пользователю. Попробуйте позже."]);
        }
        return response()->json(["success" => "Y", "message" => "Данные успешно сохранены"]);
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
        if(!\Auth::user() || !\Auth::user()->can('user-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id', 'ASC')->get();
        $userItem = $this->userRepository->getUser($id);
        $userPosts = $this->postRepository->getPreviewPostsListByUser($id, 10);
        $follow_count = $this->followRepository->countFollowToByUser($id);
        $follow_from_count = $this->followRepository->countFollowFromByUser($id);

        return view('user.show', compact(
            'userItem',
            'roles',
            'userPosts',
            'follow_count',
            'follow_from_count'
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
        if(!\Auth::user() || !\Auth::user()->can('user-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id', 'ASC')->get();
        $sexList = \DB::table('user_sex')->select(['id', 'title', 'alias'])->get();
        $userItem = $this->userRepository->getUser($id);
        $userPosts = $this->postRepository->getPreviewPostsListByUser($id, 10);
        $tags_to_post = [];
        if(!empty($userPosts)){
            $tags_to_post = $this->tagRepository->getTagsToPosts($userPosts->collect()->all());
        }

        return view('user.edit', compact(
            'userItem',
            'roles',
            'sexList',
            'userPosts',
            'tags_to_post'
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateReques $request, $id)
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('user-edit')){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав. Попробуйте позже."]);
        }

        $data = $request->all();
        $user = $this->userRepository->getEdit($id);

        $result = $user->update($data);
        if(!$result){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении данных. Попробуйте позже."]);
        }
        return response()->json(["success" => "Y", "message" => "Данные успешно сохранены"]);
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
        if(!\Auth::user() || !\Auth::user()->can('user-delete')){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав. Попробуйте позже."]);
        }

        if(!User::destroy($id)){
            return response()->json(["success" => "N", "message" => "Ошибка при удалении пользователя. Попробуйте позже."]);
        }
        return response()->json(["success" => "Y", "message" => "Пользователь успешно удален."]);
    }

    // Авторизация за выбранного пользователя
    public function authUser($id){
        if(!\Auth::user() || !\Auth::user()->can('user-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        \Auth::login(User::find($id));

        return redirect('/');
    }

    public function controlUpdate(Request $request){
        if(!\Auth::user() || !\Auth::user()->can('user-edit')){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав. Попробуйте позже."]);
        }

        // Назначаем пользователя администратором
        if(!$user = \App\Models\User\User::where('id', $request->user_id)->first()){
            return response()->json(["success" => "N", "message" => "Ошибка при получении данных пользователя. Попробуйте позже."]);
        }

        if(!$role = Role::where('id', '=', $request->user_role)->first()){
            return response()->json(["success" => "N", "message" => "Ошибка при получении роли. Попробуйте позже."]);
        }

        // Удаляем текущие роли пользователя и присваеваем новые
        if(!$user->syncRoles($role->name)){
            return response()->json(["success" => "N", "message" => "Ошибка при изменении роли пользователю. Попробуйте позже."]);
        }

        return response()->json(["success" => "Y", "message" => "Обновлено.", 'role' => $role->name]);
    }

    public function changePassword(UserUpdateReques $request){
        if(!\Auth::user() || !\Auth::user()->can('user-edit')){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав. Попробуйте позже."]);
        }

        if($request->input('password') != $request->input('password_confirm')){
            return response()->json(["success" => "N", "message" => "Неверно подтвержден пароль."]);
        }

        $request->password = bcrypt($request->password);

        $user = $this->userRepository->getEdit($request->input('user_id'));

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $result = $user->update($data);
        if(!$result){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении данных. Попробуйте позже."]);
        }
        return response()->json(["success" => "Y", "message" => "Данные успешно сохранены"]);
    }
}
