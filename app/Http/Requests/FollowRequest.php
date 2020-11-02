<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowRequest extends FormRequest
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
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id|unique:follows,to_user_id',
        ];
    }

    public function messages()
    {
        return [
            'from_user_id.required' => 'Не задан прользователь который хочет подписаться.',
            'to_user_id.required' => 'Не задан прользователь на которого осуществляется подписка.',
            'from_user_id.exists:users,id' => 'Неверно задан прользователь на которого осуществляется подписка.',
            'to_user_id.exists:users,id' => 'Неверно задан прользователь на которого осуществляется подписка.',
            //'from_user_id.unique' => 'Вы уже подписаны на этого пользователя. 1',
            'to_user_id.unique' => 'Вы уже подписаны на этого пользователя.',
        ];
    }



    /**
     * Обработка данных запроса перед валидацией
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {

        $data = $this->all();
        $data['from_user_id'] = \Auth::check() ? \Auth::user()->id : 0;
        $this->getInputSource()->replace($data);

        //dd($data);

        return parent::getValidatorInstance();
    }
}
