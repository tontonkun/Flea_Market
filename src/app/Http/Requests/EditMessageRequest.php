<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 必要に応じて、リクエストが許可されたユーザーか確認する処理を追加できます
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
            'edit_message' => 'required|max:400',
        ];
    }

    /**
     * バリデーションエラーメッセージをカスタマイズできます。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'edit_message.required' => '本文を入力してください',
            'edit_message.max' => '本文は400文字以内で入力してください。',
        ];
    }
}
