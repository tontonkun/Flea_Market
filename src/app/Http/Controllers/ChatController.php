<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;
use App\Models\Profile;

class ChatController extends Controller
{
    public function showChat($itemId)
    {
        $item = Item::with('user')->findOrFail($itemId); // 出品者も取得

        $messages = Message::with('user')->where('item_id', $itemId)->get();

        // 出品者と購入者のどちらが相手かを判断
        $myId = auth()->id();
        $sellerId = $item->seller_id;

        // 仮に purchased_item テーブルで購入者情報があるとする
        $purchase = $item->purchasedItem()->first(); // リレーションがある場合
        $buyerId = $purchase ? $purchase->purchaser_id : null;

        $chatPartner = null;

        if ($myId === $sellerId && $buyerId) {
            $chatPartner = \App\Models\User::find($buyerId);
        } elseif ($myId === $buyerId) {
            $chatPartner = \App\Models\User::find($sellerId);
        }

        $partnerProfile = $chatPartner
        ? Profile::where('user_id', $chatPartner->id)->first()
        : null;

        return view('chat', compact('item', 'messages', 'chatPartner', 'partnerProfile'));
    }

    public function sendMessage(MessageRequest $request, $itemId)
    {
        // バリデーションが成功すると、このコードが実行される

        // メッセージの保存
        $message = new Message();
        $message->user_id = auth()->id(); // ログインユーザーのID
        $message->item_id = $itemId;
        $message->content = $request->message;
        
        // 画像ファイルがアップロードされていれば保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public'); // chat_imagesフォルダに保存
            $message->image_path = $path;
        }

        $message->save();

        // チャット画面にリダイレクト
        return redirect()->route('chat', $itemId);
    }
}
