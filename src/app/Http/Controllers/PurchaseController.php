<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use App\Models\PurchasedItem;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchasePage(Request $request, Item $item)
    {
        // 未ログインの場合は itemDetail にリダイレクト
        if (!Auth::check()) {
            $errorMessage = 'ログインしてください';

            $profile = null; // 非ログインなのでプロフィールは null

            return view('itemDetail', compact('item', 'profile', 'errorMessage'));
        }

        // ログイン済みユーザーのプロフィールを取得
        $profile = Profile::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        return view('purchase', compact('item', 'profile'));
    }

    public function updatePaymentMethod(Request $request)
    {
        $paymentMethodValue = $request->input('payment_method');

        $paymentMethodLabels = [
            'convenience_store' => 'コンビニ払い',
            'credit_card' => 'カード決済',
        ];

        $displayName = $paymentMethodLabels[$paymentMethodValue] ?? $paymentMethodValue;

        // フラッシュセッションで表示用のテキスト
        session()->flash('payment_method_display', $displayName);
        // フラッシュセッションで選択状態を保持する
        session()->flash('payment_method_selected', $paymentMethodValue);

        return redirect()->back();
    }



    public function process(Request $request, Item $item)
    {
       // 購入済みチェック
        if (!$item->is_active) {
            return redirect('/')->with('error', 'この商品はすでに購入されています。');
        }

        // バリデーション（必要ならここでも可能）
        $request->validate([
            'payment_method' => 'required|in:convenience_store,credit_card',
        ]);

        // アイテムのステータス更新
        $item->is_active = false;
        $item->save();

        // セッション or プロフィールから配送先情報を取得
        $tempAddress = session('temporary_address');
        $profile = Profile::where('user_id', Auth::id())->latest()->first();

        $postal_code = $tempAddress['postal_code'] ?? $profile->postal_code;
        $address = $tempAddress['address'] ?? $profile->address;
        $building_name = $tempAddress['building_name'] ?? $profile->building_name;

        // 購入情報保存
        PurchasedItem::create([
            'purchaser_id' => Auth::id(),
            'item_id' => $item->id,
            'item_name' => $item->item_name,
            'shipping_postal_code' => $postal_code,
            'shipping_address' => $address,
            'shipping_building_name' => $building_name,
            'payment_method' => $request->input('payment_method'),
        ]);

        // 一時配送情報クリア
        session()->forget('temporary_address');

        return redirect('/')->with('success', '商品を購入しました');
    }
}
