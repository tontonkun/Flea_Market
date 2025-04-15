<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * ログイン後のリダイレクト先
     *
     * @var string
     */
    protected $redirectTo = '/'; // RouteServiceProvider::HOME でも可

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログインフォームの表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (Auth::attempt([
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ])) {
            // ✅ メール認証されてないユーザーを拒否する場合
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'メール認証が完了していません。メールをご確認ください。',
                ]);
            }

            return redirect($this->redirectTo);
        }

        return redirect()->back()->withErrors([
            'email' => 'ログイン情報が正しくありません。',
        ]);
    }

    /**
     * ログアウト処理
     */
    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        return redirect('/');
    }
}
