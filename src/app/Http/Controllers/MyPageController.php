<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Favorite;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function showMyPage()
    {
        // ログインユーザーの最新プロフィール情報を取得 
        $profile = Profile::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first();

        // ログインユーザーが出品した商品を取得
        $postedItems = Item::where('seller_id', Auth::id())
            ->where('is_active', true)  // 出品中の商品を表示
            ->get();

        // ログインユーザーが購入した商品を取得
        // purchaser_id がログインユーザーのIDである商品を取得
        $purchasedItems = Item::where('purchaser_id', Auth::id())
            ->get(); // 購入した商品（purchaser_idがログインユーザーIDのもの）

        // ログインユーザーのお気に入り商品（マイリスト）
        // お気に入りの関係を取得（例: User と Item の多対多）
        $favoriteItems = Favorite::where('user_id', Auth::id())
            ->pluck('item_id');  // お気に入りした商品の item_id を取得

        // お気に入りの商品を取得
        $favoriteItems = Item::whereIn('id', $favoriteItems)->get();

        // ビューに必要なデータを渡す
        return view('myPage', compact(
            'profile',
            'postedItems',
            'purchasedItems',
            'favoriteItems'
        ));
    }
}
