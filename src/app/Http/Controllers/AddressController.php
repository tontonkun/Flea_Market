<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChangeAddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function showAdressChangePage($item_id)
    {
        // 商品IDを使って、商品をデータベースから取得
        $item = Item::find($item_id);

        // 商品が見つからない場合のエラーハンドリング
        if (!$item) {
            return redirect()->route('home')->with('error', '商品が見つかりません。');
        }

        // addressChangeビューに商品情報を渡す
        return view('addressChange', compact('item'));
    }

    public function changeAddress(ChangeAddressRequest $request, $itemId)
    {
        $validated = $request->validated();

    session([
        'temporary_address' => [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building_name' => $request->building_name,
        ]
    ]);

    return redirect()->route('showPurchasePage', ['item' => $itemId])
                     ->with('success', '住所が一時的に変更されました');
    }
}