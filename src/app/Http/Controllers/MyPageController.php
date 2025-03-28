<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Favorite; 
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function showMyPage()
    {
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

        // おすすめ商品（全ユーザーが出品した商品を取得）
        $recommendedProducts = Product::where('is_active', true)->get(); // 出品中の商品を全て取得

        // ログインユーザーのお気に入り商品（マイリスト）
        // お気に入りの関係を取得（例: User と Product の多対多）
        $favoriteProducts = Favorite::where('user_id', Auth::id())
            ->pluck('product_id');  // お気に入りした商品の product_id を取得

        // お気に入りの商品を取得
        $favoriteProducts = Product::whereIn('id', $favoriteProducts)->get();

        // ビューに必要なデータを渡す
        return view('myPage', compact(
            'profile',
            'postedProducts',
            'purchasedProducts',
            'recommendedProducts',
            'favoriteProducts'
        ));
    }
}
