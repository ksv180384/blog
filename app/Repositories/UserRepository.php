<?php


namespace App\Repositories;


use App\Models\User\User as Model;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UserRepository extends CoreRepository
{
    public function getModelClass()
    {
        return Model::class;
    }


    /**
     * Получет данные пользователя
     * @param $id - идентификатор пользователя
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUser($id){

        $columns = [
            'users.id',
            'users.name',
            'users.email',
            'users.email_verified_at',
            'users.avatar',
            'users.avatar_select',
            'users.sex',
            'users.birthday',
            'users.residence',
            'users.description',
            'users.view_name',
            'users.view_sex',
            'users.view_birthday',
            'users.view_residence',
            'users.view_description',
            'users.show_yar_birthday',
            'users.view_sub_characters',
            'users.date_active',
            'users.date_registration',
            'users.adm',
            'users.status',
            'users.created_at',
            'users.updated_at',
            'user_sex.title AS sex_title',
            'user_sex.alias AS sex_alias',
            'user_status.title AS status_title',
            'user_status.alias AS status_alias',
            'roles.id AS role_id',
            'roles.name AS role_name',
        ];

        $user = $this->startConditions()
            ->select($columns)
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('users.id', '=', $id)
            //->toBase()
            ->first();

        return $user;
    }

    /**
     * @param int $paginate - количествл получаемых даписей
     * @return \Illuminate\Support\Collection
     */
    public function getUsersList($paginate = 5){
        //$users = User::paginate($paginate);
        $columns = [
            'users.id',
            'users.name',
            'users.email',
            'users.email_verified_at',
            'users.avatar',
            'users.avatar_select',
            'users.sex',
            'users.birthday',
            'users.residence',
            'users.description',
            'users.view_name',
            'users.view_sex',
            'users.view_birthday',
            'users.view_residence',
            'users.view_description',
            'users.show_yar_birthday',
            'users.view_sub_characters',
            'users.date_active',
            'users.date_registration',
            'users.adm',
            'users.status',
            'users.created_at',
            'users.updated_at',
            'user_sex.title AS sex_title',
            'user_sex.alias AS sex_alias',
            'user_status.title AS status_title',
            'user_status.alias AS status_alias',
            'roles.id AS role_id',
            'roles.name AS role_name',

        ];
        $users = $this->startConditions()
            ->select($columns)
            ->leftJoin('user_sex', 'user_sex.id', '=', 'users.sex')
            ->leftJoin('user_status', 'user_status.id', '=', 'users.status')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->orderBy('users.id', 'ASC')
            ->paginate($paginate);

        return $users;
    }

    /**
     * Получает список полов пользователя
     * @return \Illuminate\Support\Collection
     */
    public function getSexUserList(){
        $result = \DB::table('user_sex')->select(['id', 'title', 'alias'])->get();

        return $result;
    }

    /**
     * Получить модель для редактирования в админке
     * @param $id
     * @return mixed
     */
    public function getEdit($id){
        return $this->startConditions()->find($id);
    }
}