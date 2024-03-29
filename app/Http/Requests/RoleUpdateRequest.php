<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!auth()->check() || !\Auth::user()->can('role-create')){
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2',
            'permission' => 'exists:permissions,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Заполните поле "Название".',
            'name.min' => 'Название должено содержать не менее 2-х символов.',
            'permission.exists' => 'Неверно заданы права пользователя.',
        ];
    }
}
