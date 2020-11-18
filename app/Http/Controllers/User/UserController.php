<?php

namespace App\Http\Controllers\User;


use App\Http\Requests\UserAdmUpdateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateReques;
use App\Models\User\User;
use App\Models\User\UserSex;
use App\Repositories\FollowRepository;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    const COUNT_USERS_LIST = 10;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var FollowRepository
     */
    private $followRepository;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->userRepository = app(UserRepository::class);
        $this->postRepository = app(PostRepository::class);
        $this->tagRepository = app(TagRepository::class);
        $this->followRepository = app(FollowRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(!\Auth::user()->can('user-list')){
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
        if(!\Auth::user()->can('user-create')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id', 'ASC')->get();
        $sexList = $this->userRepository->getSexUserList();

        return view('user.create', compact('sexList', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        //
        if(!\Auth::user()->can('user-create')){
            abort(404, 'У вас недостаточно прав.');
        }


        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['email_verified_at'] = \Carbon\Carbon::now();

        if(!$User = User::create($data)){
            return response()->json(['message' => 'Ошибка при сохранении данных. Попробуйте позже.'], 404);
        }

        if(!$role = Role::find($data['user_role'])){
            return response()->json(['message' => 'Ошибка при получении роли. Попробуйте позже.'], 404);
        }

        // Удаляем текущие роли пользователя и присваеваем новые
        if(!$User->syncRoles($role->name)){
            return response()->json(['message' => 'Ошибка при изменении роли пользователю. Попробуйте позже.'], 404);
        }
        return response()->json(['message' => 'Данные успешно сохранены.', 'redirect' => route('user.edit', $User->id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        if(!\Auth::user()->can('user-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $userPosts = $this->postRepository->getPreviewPostsListByUserPublishedAll($user->id, 10);

        return view('user.show', compact(
            'user',
            'userPosts'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        if(!\Auth::user()->can('user-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id', 'ASC')->get();
        $sexList = UserSex::all();
        $userPosts = $this->postRepository->getPreviewPostsListByUser($user->id, 10);

        return view('user.edit', compact(
            'user',
            'roles',
            'sexList',
            'userPosts'
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserAdmUpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserAdmUpdateRequest $request, $id)
    {
        //
        if(!\Auth::user()->can('user-edit')){
            return response()->json(['message' => 'У вас недостаточно прав. Попробуйте позже.'], 404);
        }

        $data = $request->all();
        $user = User::find($id);

        $result = $user->update($data);
        if(!$result){
            return response()->json(['message' => 'Ошибка при сохранении данных. Попробуйте позже.'], 404);
        }
        return response()->json(['message' => 'Данные успешно сохранены.']);
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
        if(!\Auth::user()->can('user-delete')){
            return response()->json(['message' => 'У вас недостаточно прав. Попробуйте позже.'], 404);
        }

        if(!User::destroy($id)){
            return response()->json(['message' => 'Ошибка при удалении пользователя. Попробуйте позже.'], 404);
        }
        return response()->json(['message' => 'Пользователь успешно удален.']);
    }

    // Авторизация за выбранного пользователя
    public function authUser($id){
        if(!\Auth::user()->can('user-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        \Auth::login(User::find($id));

        return redirect('/');
    }

    public function controlUpdate(Request $request){
        if(!\Auth::user()->can('user-edit')){
            return response()->json(['message' => 'У вас недостаточно прав. Попробуйте позже.'], 404);
        }

        // Назначаем пользователя администратором
        if(!$user = User::find($request->user_id)){
            return response()->json(['message' => 'Ошибка при получении данных пользователя. Попробуйте позже.'], 404);
        }

        if(!$role = Role::find($request->user_role)){
            return response()->json(['message' => 'Ошибка при получении роли. Попробуйте позже.'], 404);
        }

        // Удаляем текущие роли пользователя и присваеваем новые
        if(!$user->syncRoles($role->name)){
            return response()->json(['message' => 'Ошибка при изменении роли пользователю. Попробуйте позже.'], 404);
        }

        return response()->json(['message' => 'Обновлено.', 'role' => $role->name]);
    }

    public function changePassword(UserUpdateReques $request){
        if(!\Auth::user()->can('user-edit')){
            return response()->json(['message' => 'У вас недостаточно прав. Попробуйте позже.'], 404);
        }

        $request->password = bcrypt($request->password);

        $user = User::find($request->user_id);

        $data = $request->all();
        $data['password'] = bcrypt($request['password']);
        $result = $user->update($data);
        if(!$result){
            return response()->json(['message' => 'Ошибка при сохранении данных. Попробуйте позже.'], 404);
        }
        return response()->json(['message' => 'Данные успешно сохранены']);
    }
}
