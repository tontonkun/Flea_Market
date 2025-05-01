<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sendMessageRequest extends FormRequest
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
            'message' => 'required|max:400',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048',
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
            'message.required' => '本文を入力してください',
            'message.max' => '本文は400文字以内で入力してください。',
            'image.image' => 'ファイルは画像形式でなければなりません。',
            'image.mimes' => '「.PNG」または「.JPEG」形式でアップロードしてください。',
            'image.max' => '画像のサイズは2MB以内でなければなりません。',
        ];
    }
}
