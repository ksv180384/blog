<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'img' => 'nullable|file|mimes:jpeg,jpg,gif,png|size:7168',
            'excerpt' => 'max:500',
            'content' => 'min:2',
        ];
    }

    public function messages()
    {
        return [
            'avatar.file' => 'Аватар должен быть файлом формата jpg, jpeg, gif, png.',
            'avatar.size' => 'Аватар превышает максимально допустимый размер файла (:attribute).',
            'avatar.mimes' => 'Аватар должен быть файлом формата jpeg, jpg, gif, png.',
            'user_id.required' => 'Не задан идентификатор пользователя.',
            'user_id.exists' => 'Неверно задан идентификатор пользователя.',
            'excerpt.max' => 'Поле "Коротко" должно содержать не более 5000 символов.',
            'content.min' => 'Текст поста должен содержать не менее 2-х символов.',
        ];
    }

    /**
     * Обработка данных запроса перед валидацией
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {

        $data = $this->all();
        $data['from_user_id'] = \Auth::user()->id;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
