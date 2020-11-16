<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'min:2|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => '',
            'sex' => 'nullable|exists:user_sex,id',
            'birthday' => 'date',
            'description' => 'max:5000',
            'adm' => 'digits::1',
            'user_role' => 'exists:roles,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.min' => 'Поле "Имя" должно содержать не менее 2-х символов.',
            'name.max' => 'Поле "Имя" должно содержать не более 255 символов.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'email.email' => 'Вы ввели некорректный email.',
            'password.min' => 'Пароль должен быть не короче 6 символов.',
            'password.confirmed' => 'Неверно подтвержден пароль.',
            'sex.exists' => 'Неверно задан пол.',
            'birthday.date' => 'Неверная дата рождения',
            'residence.min' => 'Поле "Место проживания" должно содержать не менее 2-х символов.',
            'residence.max' => 'Поле "Место проживания" должно содержать не более 5000 символов.',
            'description.max' => 'Поле "о себе" должно содержать не более 5000 символов.',
            'user_role.exists' => 'Неверно задана роль пользователя.',
            'adm.digits' => 'Неверно задан adm',
        ];
    }
}