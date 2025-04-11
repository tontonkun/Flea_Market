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
        'item_name' => 'required|string|max:255',
        'price' => ['required', 'regex:/^[０-９0-9]+$/'],
        'brand_name' => 'nullable|string|max:255',
        'item_img_pass' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string|max:255',
        'condition_id' => 'nullable|exists:conditions,id',
        'selected_category' => 'nullable|array',
        'selected_category.*' => 'exists:categories,category',     
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
