<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EvaluationRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\EditMessageRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeCompletedNotification;

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
            $chatPartner = \App\Models\User::with('profile')->find($buyerId);
        } elseif ($myId === $buyerId) {
            $chatPartner = \App\Models\User::with('profile')->find($sellerId);
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

        // 各商品に未読メッセージ数を付加（相手 → 自分 の未読）
        foreach ($otherItems as $otherItem) {
            $unreadCount = Message::where('item_id', $otherItem->id)
                ->where('user_id', '!=', $myId) // 相手のメッセージ
                ->whereNull('read_at') // 未読
                ->count();

            $otherItem->unread_count = $unreadCount;
        }

        // 評価済みかどうかをチェック
        $hasRated = Rating::where('evaluated_user_id', $myId)
            ->where('item_id', $itemId)
            ->exists();

        // 開いているチャットのメッセージを既読にする
        Message::where('item_id', $itemId)
            ->where('user_id', '!=', $myId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);


        // 購入者が評価したかどうか
        $hasPartnerRated = Rating::where('evaluated_user_id', $myId)
            ->where('item_id', $itemId)
            ->exists();

        // 出品者が評価済みかどうかをチェック
        $hasRated = Rating::where('evaluated_user_id', $myId)
            ->where('item_id', $itemId)
            ->exists();

        return view('chat', compact(
            'item',
            'messages',
            'chatPartner',
            'partnerProfile',
            'otherItems',
            'myId',
            'buyerId',
            'hasRated',
            'hasPartnerRated'
        ));
    }

    public function sendMessage(sendMessageRequest $request, $itemId)
    {
        // メッセージの保存
        $message = new Message();
        $message->user_id = auth()->id(); // ログインユーザーのID
        $message->item_id = $itemId;
        $message->message = $request->message;

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
    public function editMessage(editMessageRequest $request, $messageId)
    {
        $message = Message::findOrFail($messageId);

        $message->message = $request->input('edit_message');
        $message->save();

        return redirect()
            ->route('chat.show', $message->item_id)
            ->with('editedMessageId', $messageId);
    }


    // 削除メソッド
    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);
        if ($message->user_id === auth()->id()) {
            $message->delete();
        }

        return redirect()->route('chat.show', $message->item_id); // チャット画面にリダイレクト
    }

    public function evaluation(EvaluationRequest $request, $itemId)
    {
        $validated = $request->validated();

        $item = Item::findOrFail($itemId);
        $userId = auth()->id();

        // 出品者・購入者の判定
        $purchase = $item->purchasedItem()->first();
        $sellerId = $item->seller_id;
        $buyerId = $purchase ? $purchase->purchaser_id : null;

        // 評価対象ユーザーを決定
        if ($userId === $sellerId && $buyerId) {
            $evaluatedUserId = $buyerId;
        } elseif ($userId === $buyerId) {
            $evaluatedUserId = $sellerId;
        } else {
            return back()->withErrors('取引関係にないユーザーです');
        }

        DB::transaction(function () use ($item, $userId, $evaluatedUserId, $request, $sellerId) {
            // 評価を保存
            Rating::create([
                'evaluator_id' => $userId,
                'evaluated_user_id' => $evaluatedUserId,
                'rating_value' => $request->input('rating'),
                'item_id' => $item->id,
            ]);

            // 出品者が評価したときのみ in_trade を false に
            if ($userId === $sellerId) {
                $item->update(['in_trade' => false]);
            }
        });

        // 出品者に通知メール（どちらが評価しても送られる）
        Mail::to($item->seller->email)->send(new TradeCompletedNotification($item));

        return redirect('/')->with('success', '取引を完了しました。');
    }
}
