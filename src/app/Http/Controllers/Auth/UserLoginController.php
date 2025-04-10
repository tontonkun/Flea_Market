<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\UnauthorizedException;

class UserLoginController extends Controller
{
    /**
     * ログインフォームの表示
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');  // ログインフォームを表示する
    }

    /**
     * ログイン処理
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(UserLoginRequest $request)
    {
        // バリデーション済みのデータを取得
        $validatedData = $request->validated();

        // メールアドレスとパスワードで認証を試みる
        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            // 認証成功した場合はJSONレスポンスを返す
            return response()->json(['message' => 'ログインに成功しました！'], 200);
        } else {
            // 認証失敗した場合
            return response()->json(['message' => 'ログイン情報が正しくありません。'], 422);
        }
    }

    /**
     * ログアウト処理
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();  // ログアウト処理
        Session::invalidate();  // セッションを無効化
        return redirect('/login');  // ログインページにリダイレクト
    }
}
