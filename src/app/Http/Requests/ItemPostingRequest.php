<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemPostingRequest extends FormRequest
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
            'item_img_pass' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_name' => 'nullable|exists:categories,name',
            'condition_name' => 'nullable|array',
            'condition_name.*' => 'exists:conditions,name',
            'item_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名は必須です。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は1円以上である必要があります。',
        ];
    }
}
