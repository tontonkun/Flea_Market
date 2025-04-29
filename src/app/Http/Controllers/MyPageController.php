<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Favorite;
use App\Models\PurchasedItem;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function showMyPage()
    {
        $userId = Auth::id();

        // プロフィール取得
        $profile = Profile::where('user_id', $userId)->orderBy('created_at', 'desc')->first();

        // 出品中の商品取得
        $postedItems = Item::where('seller_id', $userId)
            ->where('is_active', true)
            ->get();

        // 購入済みの商品を purchased_items テーブルから取得
        $purchasedItems = PurchasedItem::where('purchaser_id', $userId)->with('item')->get();

        // お気に入りアイテム取得
        $favoriteItemIds = Favorite::where('user_id', $userId)->pluck('item_id');
        $favoriteItems = Item::whereIn('id', $favoriteItemIds)->get();

        $tradingItems = Item::where('in_trade', true)
            ->where(function ($query) use ($userId) {
                $query->where('seller_id', $userId)
                    ->orWhereHas('purchasedItem', function ($q) use ($userId) {
                        $q->where('purchaser_id', $userId);
                    });
            })
            ->get();


        return view('myPage', compact(
            'profile',
            'postedItems',
            'purchasedItems',
            'favoriteItems',
            'tradingItems'
        ));
    }
}
