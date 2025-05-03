<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Favorite;
use App\Models\PurchasedItem;
use App\Models\Message;
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
        $tradingItems = Item::with('messages')
            ->where('in_trade', true)
            ->where(function ($query) use ($userId) {
                $query->where('seller_id', $userId)
                    ->orWhereHas('purchasedItem', function ($q) use ($userId) {
                        $q->where('purchaser_id', $userId);
                    });
            })
            ->get();

        // 各商品に未読件数と最新メッセージ時間を追加
        foreach ($tradingItems as $item) {
            $unreadCount = $item->messages
                ->where('user_id', '!=', $userId)
                ->whereNull('read_at')
                ->count();

            $item->setAttribute('unread_count', $unreadCount);

            $latestMessage = $item->messages->sortByDesc('created_at')->first();
            $item->setAttribute('latest_message_time', optional($latestMessage)->created_at);
        }

        // 最新メッセージ順に並べ替え
        $tradingItems = $tradingItems->sortByDesc('latest_message_time')->values();

        // 未読メッセージ総数
        $totalUnreadCount = $tradingItems->sum('unread_count');

        // 評価の平均
        $averageRating = Rating::where('evaluated_user_id', $userId)->avg('rating_value');
        $roundedRating = round($averageRating);

        return view('myPage', compact(
            'profile',
            'postedItems',
            'purchasedItems',
            'favoriteItems',
            'tradingItems',
            'roundedRating',
            'totalUnreadCount'
        ));
    }
}
