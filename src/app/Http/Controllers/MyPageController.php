<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Favorite;
use App\Models\PurchasedItem;
use App\Models\Rating;

class MyPageController extends Controller
{
    public function showMyPage()
    {
        $userId = Auth::id();

        // プロフィール取得
        $profile = Profile::where('user_id', $userId)->orderBy('created_at', 'desc')->first();

        // 出品中の商品取得
        $postedItems = Item::where('seller_id', $userId)->get();

        // 購入済みの商品取得
        $purchasedItems = PurchasedItem::where('purchaser_id', $userId)->with('item')->get();

        // お気に入り商品取得
        $favoriteItemIds = Favorite::where('user_id', $userId)->pluck('item_id');
        $favoriteItems = Item::whereIn('id', $favoriteItemIds)->get();

        // 取引中の商品取得（出品者または購入者）
        $tradingItems = Item::with(['messages', 'purchasedItem'])
            ->where('in_trade', true)
            ->where(function ($query) use ($userId) {
                $query->where('seller_id', $userId)
                    ->orWhereHas('purchasedItem', function ($q) use ($userId) {
                        $q->where('purchaser_id', $userId);
                    });
            })
            ->get();

        // フィルタリング後の結果を格納
        $filteredTradingItems = collect();

        foreach ($tradingItems as $item) {
            $purchaserId = optional($item->purchasedItem)->purchaser_id;

            // ログインユーザーが購入者かつ評価済み → 除外
            if ($purchaserId === $userId) {
                $alreadyRated = Rating::where('item_id', $item->id)
                    ->where('evaluator_id', $userId)
                    ->exists();

                if ($alreadyRated) {
                    continue;
                }
            }

            // 未読メッセージ数
            $unreadCount = $item->messages
                ->where('user_id', '!=', $userId)
                ->whereNull('read_at')
                ->count();
            $item->setAttribute('unread_count', $unreadCount);

            // 最新メッセージ時間
            $latestMessage = $item->messages->sortByDesc('created_at')->first();
            $item->setAttribute('latest_message_time', optional($latestMessage)->created_at);

            // 自分宛の評価があるか
            $hasRating = Rating::where('item_id', $item->id)
                ->where('evaluated_user_id', $userId)
                ->exists();
            $item->setAttribute('is_completed', $hasRating);

            $filteredTradingItems->push($item);
        }

        // 最新メッセージ順に並べ替え
        $filteredTradingItems = $filteredTradingItems->sortByDesc('latest_message_time')->values();

        // 未読メッセージ総数
        $totalUnreadCount = $filteredTradingItems->sum('unread_count');

        // 評価の平均
        $averageRating = Rating::where('evaluated_user_id', $userId)->avg('rating_value');
        $roundedRating = round($averageRating);

        return view('myPage', compact(
            'profile',
            'postedItems',
            'purchasedItems',
            'favoriteItems',
            'filteredTradingItems',
            'roundedRating',
            'totalUnreadCount'
        ));
    }
}
