<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AddressController extends Controller
{
    public function showAdressChangePage($item_id)
    {
        // 商品IDを使って、商品をデータベースから取得
        $product = Product::find($item_id);

        // 商品が見つからない場合のエラーハンドリング
        if (!$product) {
            return redirect()->route('home')->with('error', '商品が見つかりません。');
        }

        // addressChangeビューに商品情報を渡す
        return view('addressChange', compact('product'));
    }

    public function changeAddress(Request $request, $product_id)
    {
        // フォームの入力値をバリデーション
        $validated = $request->validate([
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
        ]);

        // セッションに住所情報を保存
        session([
            'temp_postal_code' => $validated['postal_code'],
            'temp_address' => $validated['address'],
            'temp_building_name' => $validated['building_name'],
        ]);

        // 購入ページにリダイレクト
        return redirect()->route('purchasePage', ['product_id' => $product_id])
            ->with('success', '配送先住所が変更されました！');
    }

}