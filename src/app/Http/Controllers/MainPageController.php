<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function showMainPage(Request $request)
    {
        // 初回ログインならprofileページ、そうでない場合はメインページに遷移
        if (Auth::check() && Auth::user()->is_first_login) {
            // 初回ログインの場合、is_first_login を false にして mypage にリダイレクト
            $user = Auth::user(); // 現在のユーザーを取得
            $user->update(['is_first_login' => false]); // フラグを false に更新

            return redirect('/myPage/profile');
        }

        // 検索クエリを受け取る
        $query = $request->input('query'); // 検索キーワード

        // おすすめ商品（全ユーザーが出品した商品）
        if ($query) {
            // 検索クエリがある場合、その条件で商品を絞り込み
            $recommendedItems = Item::where('is_active', true)
                ->where('item_name', 'LIKE', "%{$query}%") // 商品名に部分一致
                ->get();
        } else {
            // 検索クエリがない場合、すべての商品を表示
            $recommendedItems = Item::where('is_active', true)->get();
        }

        // ログインユーザーのお気に入り商品
        if (auth()->check()) {
            // お気に入り商品も検索条件を適用する
            $favoriteQuery = $query; // お気に入り商品にも検索条件を適用
            if ($favoriteQuery) {
                // お気に入り商品の検索
                $favoriteItems = auth()->user()->favorites()
                    ->whereHas('item', function ($query) use ($favoriteQuery) {
                        $query->where('item_name', 'LIKE', "%{$favoriteQuery}%");
                    })
                    ->get();
            } else {
                // お気に入り商品があれば全件取得
                $favoriteItems = auth()->user()->favorites;
            }
        } else {
            $favoriteItems = collect(); // ログインしていない場合は空のコレクションを返す
        }

        return view('mainPage', compact('recommendedItems', 'favoriteItems', 'query'));
    }
}
