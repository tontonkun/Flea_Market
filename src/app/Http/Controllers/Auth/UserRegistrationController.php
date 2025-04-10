<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserRegistrationController extends Controller
{
    /**
     * ユーザー登録フォームの表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register'); // 登録フォームのビューを返す
    }

    /**
     * ユーザー登録処理
     *
     * @param  \App\Http\Requests\UserRegistrationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRegistrationRequest $request)
    {
        // バリデーション済みのデータを取得
        $validatedData = $request->validated();

        // ユーザーの作成
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // event(new Registered($user)); // メール認証イベントを発行

        // ユーザー登録後に自動的にログイン
        Auth::login($user);

        // 登録成功後のリダイレクト
        return redirect('/myPage/profile')->with('status', session('status'));

    }
}