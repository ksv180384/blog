<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'img' => 'image',
            'excerpt' => 'max:500',
            'content' => 'string',
            'published_at' => 'date_format:"d-m-Y H:i"',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'Неверно задан идентификатор пользователя.',
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
