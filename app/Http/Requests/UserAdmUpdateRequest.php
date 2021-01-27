<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAdmUpdateRequest extends FormRequest
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
            'email' => 'email',
            'password' => 'min:6',
            'avatar' => 'nullable|file|mimes:jpeg,jpg,gif,png|max:7168',
            'sex' => 'nullable|exists:user_sex,id',
            'birthday' => 'nullable|date|date_format:Y-m-d',
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
            'avatar.size' => 'Аватар превышает максимально допустимый размер файла (:max).',
            'avatar.mimes' => 'Аватар должен быть файлом формата jpeg, jpg, gif, png.',
            'email.email' => 'Вы ввели некорректный email.',
            'sex.exists' => 'Неверно задан пол.',
            'birthday.date' => 'Неверная дата рождения',
            'birthday.date_format' => 'Неверный формат даты рождения',
            'residence.min' => 'Поле "Место проживания" должно содержать не менее 2-х символов.',
            'residence.max' => 'Поле "Место проживания" должно содержать не более 5000 символов.',
            'description.max' => 'Поле "о себе" должно содержать не более 5000 символов.',
            'status.exists' => 'Неверно задан статус.',
            'adm.digits' => 'Неверно задан adm',
        ];
    }

    /**
     * Обработка данных запроса перед валидацией
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {

        $data = $this->all();

        $data['birthday'] = !empty($data['birthday']) ? date('Y-m-d', strtotime($data['birthday'])) : null;


        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
