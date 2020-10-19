<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'userName' => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:3'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'userName.required' => 'Введите свое имя',
            'email.unique'   => 'Данная электронная почта уже занята',
            'email.required'   => 'Введите свою электронную почту',
            'email.email'   => 'Некорректная электронная почта',
            'password.required' => 'Введите пароль',
            'password.min' => 'Минимальная длина пароля - 3 символа'
        ];
    }
}
