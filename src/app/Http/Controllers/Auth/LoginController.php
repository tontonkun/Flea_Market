<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\UnauthorizedException;

class LoginController extends Controller
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
    public function login(LoginRequest $request)
    {
        // バリデーション済みのデータを取得
        $validatedData = $request->validated();

        // メールアドレスとパスワードで認証を試みる
        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            // 認証成功した場合は、トップページにリダイレクト
            return redirect('/');
        } else {
            // 認証失敗した場合
            return redirect()->back()->withErrors([
                'email' => 'ログイン情報が正しくありません。',
            ]);
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
        return redirect('/');  // ログインページにリダイレクト
    }
}
