<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Favorite;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function showMainPage()
    {
        // ユーザーが初回ログインかどうかを判定
        if (Auth::check() && Auth::user()->is_first_login) {
            // 初回ログインの場合、is_first_login を false にして mypage にリダイレクト
            $user = Auth::user(); // 現在のユーザーを取得
            $user->update(['is_first_login' => false]); // フラグを false に更新

            return redirect('/myPage/profile');
        }

        // 初回ログインでない場合はメインページに遷移

        // おすすめ商品（全ユーザーが出品した商品）
        $recommendedProducts = Product::where('is_active', true)->get();
         // 出品中の商品を取得

        // ログインユーザーのお気に入り商品
        if (auth()->check()) {
            $favoriteProducts = auth()->user()->favorites;
        } else {
            $favoriteProducts = collect(); // ログインしていない場合は空のコレクションを返す
        }

        // ビューにデータを渡す
        return view('mainPage', compact('recommendedProducts', 'favoriteProducts'));
    }
}
