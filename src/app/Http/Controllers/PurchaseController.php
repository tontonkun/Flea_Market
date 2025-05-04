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

    // 購入処理の共通化
    private function handlePurchase(Item $item, $paymentMethod)
    {
        // アイテムのステータス更新
        $item->is_active = false;
        $item->in_trade = true;
        $item->save();

        // セッションまたはプロフィールから配送先情報を取得
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
            'payment_method' => $paymentMethod, // 支払い方法
        ]);

        // 一時配送情報をクリア
        session()->forget('temporary_address');
    }

    public function process(Request $request, Item $item)
    {
        // StripeのAPIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // 購入済みチェック
        if (!$item->is_active) {
            return redirect('/')->with('error', 'この商品はすでに購入されています。');
        }

        // バリデーション
        $request->validate([
            'payment_method' => 'required|in:convenience_store,credit_card',
        ]);

        // 支払い方法がコンビニ払いの場合
        if ($request->input('payment_method') == 'convenience_store') {
            // 購入処理を実行
            $this->handlePurchase($item, 'convenience_store');

            // トップページにリダイレクト
            return redirect('/')->with('success', '購入手続きが完了しました。');
        }

        // クレジットカード決済の場合、Stripe Checkoutセッションを作成
        if ($request->input('payment_method') == 'credit_card') {
            Stripe::setApiKey(config('services.stripe.secret'));

            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $item->item_name,
                            ],
                            'unit_amount' => $item->price * 100, // JPYは最小単位が円なので100倍
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['item' => $item->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('showPurchasePage', ['item' => $item->id]),
            ]);

            return redirect($checkoutSession->url);
        }

        return redirect('/')->with('error', '支払い方法が選択されていません。');
    }

    public function success(Request $request, Item $item)
    {
        // StripeのAPIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe Checkoutセッションの取得
        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

        // 決済成功確認
        if ($session->payment_status == 'paid') {
            // 購入済みチェック
            if (!$item->is_active) {
                return redirect('/')->with('error', 'この商品はすでに購入されています。');
            }

            // 購入処理を実行
            $this->handlePurchase($item, $session->payment_method_types[0]);

            return redirect('/')->with('success', '購入手続きが完了しました。');
        }

        // 決済が失敗した場合
        return redirect('/')->with('error', '決済が失敗しました');
    }
}
