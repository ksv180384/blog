<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCommentRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|max:500',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists:users,id' => 'Неверно задан прользователь.',
            'user_id.required' => 'Неверно задан прользователь.',
            'post_id.exists:users,id' => 'Неверно задан пост.',
            'post_id.required' => 'Неверно задан пост.',
            'comment.required' => 'Вы не можете отправить пустой комментарий.',
            'comment.max:500' => 'Комментарий не должен быть больше 500 символов.',
        ];
    }

    /**
     * Обработка данных запроса перед валидацией
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {

        $data = $this->all();
        $data['user_id'] = \Auth::check() ? \Auth::user()->id : 0;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
