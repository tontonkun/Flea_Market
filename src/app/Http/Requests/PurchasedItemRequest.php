<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchasedItemRequest extends FormRequest
{
    /**
     * 購入リクエストが認可されているかどうか（今回は全ユーザーOK）
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules()
    {
        return [
            'shipping_postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_building_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'shipping_postal_code.required' => '郵便番号は必須です。',
            'shipping_postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'shipping_address.required' => '住所は必須です。',
            'shipping_address.max' => '住所は255文字以内で入力してください。',
            'shipping_building_name.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
