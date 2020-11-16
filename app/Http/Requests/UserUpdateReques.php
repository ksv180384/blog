<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateReques extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
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
            // only_user - уникальное поле и приандлежит текущему пользователю
            'email' => 'email|only_user',
            'password' => 'min:6|confirmed',
            'avatar' => 'nullable|file|mimes:jpeg,jpg,gif,png|size:7168',
            'sex' => 'nullable|exists:user_sex,id',
            'birthday' => 'nullable|date',
            'residence' => 'max:100',
            'description' => 'max:5000',
            'adm' => 'digits::1',
            'status' => 'exists:user_status,id',
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
            'avatar.file' => 'Аватар должен быть файлом формата jpg, jpeg, gif, png.',
            'avatar.size' => 'Аватар превышает максимально допустимый размер файла (:attribute).',
            'avatar.mimes' => 'Аватар должен быть файлом формата jpeg, jpg, gif, png.',
            'email.only_user' => 'Пользователь с таким email уже существует.',
            'email.email' => 'Вы ввели некорректный email.',
            'password.min' => 'Пароль должен быть не короче 6 символов.',
            'password.confirmed' => 'Неверно подтвержден пароль.',
            'sex.exists' => 'Неверно задан пол.',
            'birthday.date' => 'Неверная дата рождения',
            'residence.min' => 'Поле "Место проживания" должно содержать не менее 2-х символов.',
            'residence.max' => 'Поле "Место проживания" должно содержать не более 5000 символов.',
            'description.max' => 'Поле "о себе" должно содержать не более 5000 символов.',
            'adm.digits' => 'Неверно задан adm',
        ];
    }
}
