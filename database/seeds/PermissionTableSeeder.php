<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'role-list',
            ],
            [
                'name' => 'role-create',
            ],
            [
                'name' => 'role-edit',
            ],
            [
                'name' => 'role-delete',
            ],
            [
                'name' => 'user-list',
            ],
            [
                'name' => 'user-create',
            ],
            [
                'name' => 'user-edit',
            ],
            [
                'name' => 'user-delete',
            ],
            [
                'name' => 'blog-posts-control',
            ],
            [
                'name' => 'blog-posts-published',
            ],
            [
                'name' => 'blog-posts-edit',
            ],
            [
                'name' => 'blog-posts-delete',
            ],
        ];
        $permissionList = [];
        foreach ($permissions as $permission){
            $permissionList[] = Permission::create($permission);
        }

        $roles = [
            [
                'name' => 'Администратор',
            ],
            [
                'name' => 'Пользователь',
            ],
        ];
        $rolesList = [];
        foreach ($roles as $role){
            $rolesList[] = Role::create($role);
        }


        // Добавляем права администратору
        foreach ($permissionList as $permissionItem){
            $rolesList[0]->givePermissionTo($permissionItem);
        }

        // Назначаем пользователя администратором
        $user = \App\Models\User\User::where('id',1)->first();
        //$user->assignRole(['Администратор']);
        $user->assignRole($rolesList[0]->name);
    }
}
