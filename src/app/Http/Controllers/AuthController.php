<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showTopPage()
    {

        // ユーザーが初回ログインかどうかを判定
        if (Auth::check() && Auth::user()->is_first_login) {
            // 初回ログインの場合、is_first_login を false にして mypage にリダイレクト
            $user = Auth::user(); // 現在のユーザーを取得
            $user->update(['is_first_login' => false]); // フラグを false に更新

            return redirect('profile');
        }

        // 初回ログインでない場合はトップページに遷移
        return view('topPage');
    }

    public function showMyPage()
    {
        return view('myPage');
    }
}
