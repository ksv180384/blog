<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAvatarUpdateRequest extends FormRequest
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
            //
            'avatar' => 'nullable|file|mimes:jpeg,jpg,gif,png|max:7168',
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
            'avatar.file' => 'Аватар должен быть файлом.',
            'avatar.max' => 'Аватар превышает максимально допустимый размер файла (:max).',
            'avatar.mimes' => 'Аватар должен быть файлом формата jpg, jpeg, gif, png.',
        ];
    }
}
