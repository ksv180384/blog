<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    const PAGE_ITEMS = 20;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!\Auth::user()->can('role-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id', 'ASC')->paginate(self::PAGE_ITEMS);
        return view('admin.role.list', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(!\Auth::user()->can('role-create')){
            abort(404, 'У вас недостаточно прав.');
        }

        $permission = Permission::all();
        return view('admin.role.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        //
        if(!\Auth::user()->can('role-create')){
            return response()->json(['message' => 'У вас недостаточно прав.'], 404);
        }
        if(!$role = Role::create(['name' => $request->input('name')])){
            return response()->json(['message' => 'Ошибка при сохранении роли.'], 404);
        }
        if(!$role->syncPermissions($request->input('permission'))){
            return response()->json(['message' => 'Ошибка при сохранении прав роли.'], 404);
        }

        return response()->json([
                'message' => 'Роль "' . $role->name . '" успешно добавлена',
                'redirect' => route('roles.index'),
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
        if(!\Auth::user()->can('role-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $role->id)
            ->get();

        return view('admin.role.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
        if(!\Auth::user()->can('role-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        $permission = Permission::all();
        $rolePermissions = \DB::table('role_has_permissions')->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('admin.role.edit',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        //
        if(!\Auth::user()->can('role-edit')){
            return response()->json(['message' => 'У вас недостаточно прав.'], 404);
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        if(!$role->save()){
            return response()->json(['message' => 'Ошибка при сохранении роли.'], 404);
        }

        if(!$role->syncPermissions($request->input('permission'))){
            return response()->json(['message' => 'Ошибка при сохранении прав роли.'], 404);
        }

        return response()->json(['message' => 'Успешно сохранено.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!\Auth::user()->can('role-create')){
            return back()->withErrors(['msg' => 'У вас недостаточно прав'])->withInput();
        }
        //
        \DB::table('roles')->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success','Роль успешно удалена');
    }
}
