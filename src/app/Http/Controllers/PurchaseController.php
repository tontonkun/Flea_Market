<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchasePage(Request $request)
    {
        // 商品IDを取得
        $itemId = $request->query('itemId');

        // 商品情報を取得
        $item = Item::findOrFail($itemId);

        // ログインユーザーのプロフィール情報を取得
        $profile = Profile::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first();

        // 購入ページを表示
        return view('purchase', compact('item', 'profile'));
    }

    public function process(Request $request, Item $item)
    {
        // 購入済みかチェック
        if (!$item->is_active) {
            return redirect('/')->with('error', 'この商品はすでに購入されています。');
        }

        // 購入処理
        $item->is_active = false;
        $item->purchaser_id = Auth::id();  // ログイン中のユーザーID
        $item->save();

        return redirect('/')->with('success', '商品を購入しました');
    }
}
