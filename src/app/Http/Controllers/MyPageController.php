<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Profile;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function showMyPage()
    {
        // ユーザーが初回ログインかどうかを判定
        if (Auth::check() && Auth::user()->is_first_login) {
            // 初回ログインの場合、is_first_login を false にして mypage にリダイレクト
            $user = Auth::user(); // 現在のユーザーを取得
            $user->update(['is_first_login' => false]); // フラグを false に更新

            return redirect('/myPage/profile');
        }

        // 初回ログインでない場合はトップページに遷移
        // ログインユーザーのプロフィール情報を取得 
        $profile = Profile::where('user_id', Auth::id())->first();

        // ログインユーザーが出品した商品を取得
        $postedProducts = Product::where('user_id', Auth::id())
            ->where('is_active', true)  // 出品中の商品を表示
            ->get();

        // ログインユーザーが購入した商品を取得
        $purchasedProducts = Product::where('user_id', Auth::id())
            ->where('is_active', false) // 購入した商品（is_activeがfalse）を取得
            ->get();

        // ビューにプロフィール、出品商品、購入商品を渡す
        return view('myPage', compact('profile', 'postedProducts', 'purchasedProducts'));
    }
}
