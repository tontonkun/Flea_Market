<?php

namespace App\Http\Requests;

use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // ユーザーがこのリクエストを行えるかどうかを確認する
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',  // メールアドレスは必須、正しい形式でなければならない
            'password' => 'required|string',  // パスワードは必須、文字列である必要がある
        ];
    }

    /**
     * カスタムエラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '正しいメールアドレスを入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }

     /**
     * バリデーション後の追加チェック
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::where('email', $this->email)->first();

            // ユーザーが見つからない場合
            if (!$user) {
                $validator->errors()->add('email', 'ログイン情報が登録されていません');
            } elseif (!Hash::check($this->password, $user->password)) {
                // パスワードが一致しない場合
                $validator->errors()->add('password', 'パスワードが間違っています');
            }
        });
    }
}
