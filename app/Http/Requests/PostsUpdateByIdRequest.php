<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostsUpdateByIdRequest extends FormRequest
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
            'price'       => 'required',
            'title'       => 'required',
            'mainContent' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'price.required'       => 'Введите цену',
            'title.required'       => 'Введите название',
            'mainContent.required' => 'Введите основной текст',
        ];
    }
}
