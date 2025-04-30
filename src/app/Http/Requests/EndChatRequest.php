<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndChatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価は必須です。',
            'rating.integer' => '評価は数値で入力してください。',
            'rating.min' => '評価は1以上にしてください。',
            'rating.max' => '評価は5以下にしてください。',
        ];
    }
}
