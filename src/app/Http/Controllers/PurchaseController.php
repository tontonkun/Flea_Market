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

    public function purchaseItem(Request $request, $itemId)
    {
        // stripeの実装後にはこれを削除
        $item->purchaser_id = Auth::id();  
        $item->is_active = false; 
        $item->save();

        // アイテムの情報を取得
        $item = Item::findOrFail($itemId);

        // 支払方法の選択を取得
        $paymentMethod = $request->input('payment_method');

        // Stripeの秘密鍵を設定
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            // Stripe Checkoutセッションの作成
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $item->item_name,
                            ],
                            'unit_amount' => $item->price * 100,  // 金額は最小単位（円 -> セントに変換）
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',  // 決済モード
                'success_url' => route('purchase.success', ['itemId' => $itemId]),  // 成功時にリダイレクトされるURL
                'cancel_url' => route('purchase.cancel', ['itemId' => $itemId]),    // キャンセル時にリダイレクトされるURL
            ]);

            // Stripe Checkoutページにリダイレクト
            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            // エラーが発生した場合の処理
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // 決済完了処理
    public function completePurchase(Request $request, $itemId)
    {
        // アイテムの情報を取得
        $item = Item::findOrFail($itemId);

        // Stripeで決済Intentの確認
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        try {
            // PaymentIntentの情報を取得
            $paymentIntent = \Stripe\PaymentIntent::retrieve($request->payment_intent);

            if ($paymentIntent->status == 'succeeded') {
                // 購入が成功した場合、ログインユーザーを購入者として設定
                //$item->purchaser_id = Auth::id(); 
                // 商品を非アクティブに設定（購入済み） 
                //$item->is_active = false; 
                //$item->save();

                // 購入完了画面へリダイレクト
                return redirect()->route('purchase.success')->with('success', '購入が完了しました！');
            } else {
                // 決済失敗
                return redirect()->route('purchase.failed')->withErrors(['error' => '決済に失敗しました。']);
            }
        } catch (\Exception $e) {
            return redirect()->route('purchase.failed')->withErrors(['error' => $e->getMessage()]);
        }
    }

    // 決済キャンセル処理
    public function cancelPurchase($itemId)
    {
        // キャンセル処理（例えば、キャンセルメッセージを表示）
        return redirect()->route('purchase.failed')->withErrors(['error' => '決済がキャンセルされました。']);
    }
}
