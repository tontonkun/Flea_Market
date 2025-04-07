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

        // おすすめ商品（全ユーザーが出品した商品）を取得
        $recommendedItems = $this->getRecommendedItems($query);

        // ログインユーザーのお気に入り商品を取得
        $favoriteItems = $this->getFavoriteItems($query);

        // ビューにデータを渡す
        return view('mainPage', compact('recommendedItems', 'favoriteItems', 'query'));
    }

    /**
     * 商品の検索処理を担当するメソッド
     */
    protected function getRecommendedItems($query = null)
    {
        $queryBuilder = Item::where('is_active', true)
            ->where(function ($q) {
                // seller_idがnullのものか、ログインユーザーが出品していないもの
                $q->whereNull('seller_id')
                    ->orWhere('seller_id', '!=', auth()->id());
            });

        if ($query) {
            $queryBuilder->where('item_name', 'LIKE', "%{$query}%");
        }

        return $queryBuilder->get();
    }

    /**
     * ログインユーザーのお気に入り商品を取得するメソッド
     */
    protected function getFavoriteItems($query = null)
    {
        if (!auth()->check()) {
            return collect(); // ログインしていない場合は空のコレクションを返す
        }

        $favoriteItemsQuery = auth()->user()->favorites();

        if ($favoriteItemsQuery->count() > 0) {
            if ($query) {
                // `favorites` テーブルを経由して `Item` モデルの絞り込みを行う
                return $favoriteItemsQuery->whereHas('items', function ($itemQuery) use ($query) {
                    // 'items' は `favorites` テーブルに関連するリレーション名
                    $itemQuery->where('item_name', 'LIKE', "%{$query}%");
                })->get();
            } else {
                // 検索クエリがなければ、お気に入り商品の全件取得
                return $favoriteItemsQuery->get();
            }
        }

        // お気に入りが空の場合は空のコレクションを返す
        return collect();
    }

}
