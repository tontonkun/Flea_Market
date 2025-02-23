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
            // 初回ログインの場合、mypageにリダイレクト
            return redirect('/myPage');
        }

        return view('topPage');
    }

    public function showMyPage()
    {
        return view('mypage');
    }
}
