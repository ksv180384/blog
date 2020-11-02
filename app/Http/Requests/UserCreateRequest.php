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
            'password' => 'required|string|min:8|confirmed',
            'avatar' => '',
            'avatar_select' => '',
            'sex' => 'nullable|exists:user_sex,id',
            'birthday' => 'date',
            'residence' => '',
            'description' => 'max:5000',
            'view_name' => 'digits::1',
            'view_sex' => 'digits::1',
            'view_birthday' => 'digits::1',
            'view_residence' => 'digits::1',
            'view_description' => 'digits::1',
            'show_yar_birthday' => 'digits::1',
            'view_sub_characters' => 'digits::1',
            'date_active' => 'date',
            'adm' => 'digits::1',
            'status' => 'exists:user_status,id',
        ];
    }
}