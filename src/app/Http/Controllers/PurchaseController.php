<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchasePage(Request $request)
    {
        // 商品IDを取得
        $productId = $request->query('productId');

        // 商品情報を取得
        $product = Product::findOrFail($productId);

        // ログインユーザーのプロフィール情報を取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // 購入ページを表示
        return view('purchase', compact('product', 'profile'));
    }


    public function processPurchase(Request $request, $product_id)
    {
        // ログインしていない場合はログインページにリダイレクト
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 商品情報を取得
        $product = Product::findOrFail($product_id);

        // 購入処理を実行（例: 注文をデータベースに追加）
        $order = new Order();
        $order->user_id = Auth::id();
        $order->product_id = $product->id;
        $order->status = 'pending'; // 注文ステータス（仮）
        $order->save();

        // 注文完了後のページにリダイレクト
        return redirect()->route('order.complete', ['order_id' => $order->id]);
    }
}
