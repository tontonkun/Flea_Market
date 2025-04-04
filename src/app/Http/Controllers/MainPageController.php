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
                ->where('item_name', 'LIKE', "%{$query}%")
                ->get();
        } else {
            // 検索クエリがない場合、すべての商品を表示
            $recommendedItems = Item::where('is_active', true)->get();
        }

        // ログインユーザーのお気に入り商品
        if (auth()->check()) {
            // お気に入り商品の検索
            $favoriteItemsQuery = auth()->user()->favorites(); // favoritesリレーションを取得

            if ($query) {
                // `favorites` テーブルを経由して `Item` モデルの絞り込みを行う
                $favoriteItems = $favoriteItemsQuery->whereHas('item', function ($itemQuery) use ($query) {
                    // `item` リレーションを使って `item_name` をLIKE検索
                    $itemQuery->where('item_name', 'LIKE', "%{$query}%");
                })->get();
            } else {
                // 検索クエリがなければ、お気に入り商品の全件取得
                $favoriteItems = $favoriteItemsQuery->get();
            }
        } else {
            // ログインしていない場合は空のコレクションを返す
            $favoriteItems = collect();
        }

        // ビューにデータを渡す
        return view('mainPage', compact('recommendedItems', 'favoriteItems', 'query'));
    }
}
