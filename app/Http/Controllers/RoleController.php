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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('role-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $roles = Role::orderBy('id','ASC')->paginate(self::PAGE_ITEMS);
        return view('admin.role.list',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * self::PAGE_ITEMS);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('role-create')){
            abort(404, 'У вас недостаточно прав.');
        }

        $permission = Permission::get();
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

        if(!$role = Role::create(['name' => $request->input('name')])){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении роли."]);
        }
        if(!$role->syncPermissions($request->input('permission'))){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении прав роли."]);
        }

        return response()->json(
            [
                "success" => "Y",
                "message" => 'Роль "' . $role->name . '" успешно добавлена',
                "redirect" => route('roles.index'),
            ]
        );
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
        if(!\Auth::user() || !\Auth::user()->can('role-list')){
            abort(404, 'У вас недостаточно прав.');
        }

        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('admin.role.show',compact('role','rolePermissions'));
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
        if(!\Auth::user() || !\Auth::user()->can('role-edit')){
            abort(404, 'У вас недостаточно прав.');
        }

        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = \DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


        return view('admin.role.edit',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        //
        if(!\Auth::user() || !\Auth::user()->can('role-edit')){
            return response()->json(["success" => "N", "message" => "У вас недостаточно прав."]);
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        if(!$role->save()){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении роли."]);
        }

        if(!$role->syncPermissions($request->input('permission'))){
            return response()->json(["success" => "N", "message" => "Ошибка при сохранении прав роли."]);
        }

        return response()->json(["success" => "Y", "message" => "Успешно сохранено."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!\Auth::user()->can('role-create') || !auth()->check()){
            return back()->withErrors(['msg' => 'У вас недостаточно прав'])->withInput();
        }
        //
        \DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
            ->with('success','Роль успешно удалена');
    }
}
