<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest; 

class ItemController extends Controller
{
    public function showDetail($id)
    {
        $item = Item::with('condition', 'category', 'comments')->findOrFail($id);

        $favoriteCount = Favorite::where('item_id', $id)->count();


        $commentCount = Comment::where('item_id', $id)->count();

        $profile = Profile::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first();

        $userName = $profile ? $profile->user_name : null;

        return view('itemDetail', compact('item', 'favoriteCount', 'commentCount', 'userName', 'profile'));
    }

    public function addFavorite($itemId)
    {
        // ユーザーがログインしていない場合
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'お気に入り登録にはログインが必要です');
        }

        $userId = auth()->id(); // 現在ログイン中のユーザーIDを取得

        // すでにお気に入りに登録されている場合は削除し、登録されていない場合は追加
        $favorite = Favorite::where('user_id', $userId)->where('item_id', $itemId)->first();

        if ($favorite) {
            // お気に入りに登録されているので削除
            $favorite->delete();
        } else {
            // お気に入りに登録されていないので追加
            Favorite::create([
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);
        }

        // 商品詳細ページにリダイレクト
        return redirect()->route('item.showDetail', ['id' => $itemId]);
    }

    public function addComment(CommentRequest $request, $id)
    {
        // バリデーションが自動的に適用される
        $item = Item::findOrFail($id);
        $comment = new Comment();
        $comment->item_id = $item->id;
        $comment->user_id = auth()->id();
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('item.showDetail', ['id' => $id])->with('success', 'コメントが送信されました');
    }
}