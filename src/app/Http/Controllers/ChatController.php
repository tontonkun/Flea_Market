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

        $purchase = $item->purchasedItem()->first(); 
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

        // その他の取引情報を取得
        $otherItems = Item::where('in_trade', true)
            ->where(function ($query) use ($myId) {
                $query->where('seller_id', $myId)
                    ->orWhereHas('purchasedItem', function ($q) use ($myId) {
                        $q->where('purchaser_id', $myId);
                    });
            })
            ->where('id', '!=', $itemId) // 今開いているアイテムは除く
            ->get();

        return view('chat', compact('item', 'messages', 'chatPartner', 'partnerProfile', 'otherItems', 'myId'));
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
        return redirect()->route('chat.show', $itemId);
    }

    // 編集メソッド
    public function edit($messageId)
    {
        $message = Message::find($messageId);

        // 編集画面に遷移する
        return view('chat.edit', compact('message'));
    }

    // 削除メソッド
    public function destroy($messageId)
    {
        $message = Message::find($messageId);
        if ($message->user_id === auth()->id()) {
            $message->delete();
        }

        return redirect()->route('chat.show', $message->item_id); // チャット画面にリダイレクト
    }
}
