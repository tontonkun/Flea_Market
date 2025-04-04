<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use Stripe\Stripe;
use Stripe\PaymentIntent;
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
        // アイテムの情報を取得
        $item = Item::findOrFail($itemId);

        // 支払方法の選択を取得
        $paymentMethod = $request->input('payment_method');

        // Stripeの秘密鍵を設定
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            // PaymentIntentの作成（決済処理のため）
            $paymentIntent = PaymentIntent::create([
                'amount' => $item->price * 100,  // 金額はセント単位（例：1000円なら100000）
                'currency' => 'jpy',  // 日本円
                'metadata' => ['item_id' => $item->id],
                'payment_method_types' => ['card'], // 支払い方法のタイプ（カード）
            ]);

            // 決済処理が成功した場合のリダイレクト（Stripeのフロントエンド用のクライアント秘密鍵を返す）
            return view('purchase.stripe', [
                'clientSecret' => $paymentIntent->client_secret,  // クライアント側で使う秘密鍵
                'item' => $item,  // アイテム情報をビューに渡す
            ]);
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

        // 決済が成功したかを確認
        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent);

            if ($paymentIntent->status == 'succeeded') {
                // 決済成功処理（データベースに記録など）
                // 購入情報をデータベースに保存などの処理をここで行う

                // 購入完了画面へリダイレクト
                return redirect()->route('purchase.success');
            } else {
                // 決済失敗
                return redirect()->route('purchase.failed');
            }
        } catch (\Exception $e) {
            return redirect()->route('purchase.failed')->withErrors(['error' => $e->getMessage()]);
        }
    }
}
