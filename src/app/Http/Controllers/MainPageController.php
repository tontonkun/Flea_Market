<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function showMainPage(Request $request)
    {
        // 初回ログインならprofileページ、そうでない場合はメインページに遷移
        $user = User::find(Auth::id());

        if ($user && $user->is_first_login) {
            $user->is_first_login = false;
            $user->save();

            // セッションに入ってる "status" を再度 with で渡す
            return redirect('/myPage/profile')->with('status', session('status'));
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
     * 商品一覧を取得するメソッド
     */
    protected function getRecommendedItems($query = null)
    {
        $queryBuilder = Item::query();

        if (auth()->check()) {
            // ログイン中：is_active が true で、自分が出品していない商品（seller_idがnullの商品も含める）
            $queryBuilder->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('seller_id')
                        ->orWhere('seller_id', '!=', auth()->id());
                });
        } else {
            // 未ログイン：出品者関係なし（全てのseller_idの商品）
            $queryBuilder->where(function ($q) {
                $q->whereNull('seller_id')
                    ->orWhereNotNull('seller_id'); // 実質全て
            });
        }

        if ($query) {
            $queryBuilder->where('item_name', 'LIKE', "%{$query}%");
        }

        return $queryBuilder->get();
    }

    /**
     * お気に入り商品を取得するメソッド
     */
    protected function getFavoriteItems($query = null)
    {
        if (!auth()->check()) {
            return collect(); // ログインしていない場合は空のコレクションを返す
        }

        $favoriteItemsQuery = auth()->user()->favorites();

        if ($favoriteItemsQuery->count() > 0) {
            // お気に入り商品の中で、自分が出品していない商品をフィルタリング
            $favoriteItemsQuery = $favoriteItemsQuery->whereHas('user', function ($itemQuery) {
                // 'items' テーブルを経由して seller_id が現在のユーザーの ID と一致しない商品を絞り込み
                $itemQuery->where(function ($q) {
                    $q->whereNull('seller_id')
                        ->orWhere('seller_id', '!=', auth()->id());
                });
            });

            // 検索クエリがあれば絞り込み
            if ($query) {
                $favoriteItemsQuery->whereHas('user', function ($itemQuery) use ($query) {
                    $itemQuery->where('item_name', 'LIKE', "%{$query}%");
                });
            }
            return $favoriteItemsQuery->get();
        }
        // お気に入りが空の場合は空のコレクションを返す
        return collect();
    }
}
