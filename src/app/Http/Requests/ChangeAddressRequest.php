<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // このリクエストは誰でも実行できるとする場合はtrue
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/', // 郵便番号（例: 123-4567）
            'address' => 'required|string|max:255',           // 住所（文字列、最大255文字）
            'building_name' => 'nullable|string|max:255',     // 建物名（任意、最大255文字）
        ];
    }

    /**
     * バリデーションエラーメッセージのカスタマイズ
     */
    public function messages()
    {
        return [
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'address.required' => '住所は必須です。',
            'address.string' => '住所は文字列で入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building_name.string' => '建物名は文字列で入力してください。',
            'building_name.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
