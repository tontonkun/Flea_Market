<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showDetail($id)
    {
        $product = Product::with('condition', 'category', 'comments')->findOrFail($id);

        $favoriteCount = Favorite::where('product_id', $id)->count();


        $commentCount = Comment::where('product_id', $id)->count();

        $profile = Profile::where('user_id', Auth::id())->first();

        $userName = $profile ? $profile->user_name : null;

        return view('productDetail', compact('product', 'favoriteCount', 'commentCount', 'userName', 'profile'));


    }

    public function addFavorite($productId)
    {
        $userId = auth()->id(); // 現在ログイン中のユーザーIDを取得

        // すでにお気に入りに登録されていない場合のみ追加
        if (!Favorite::where('user_id', $userId)->where('product_id', $productId)->exists()) {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }

        return redirect()->route('product.detail', ['id' => $productId]); // 商品詳細ページにリダイレクト
    }

    public function addComment(Request $request, $id)
    {
        // コメントを保存
        $product = Product::findOrFail($id);
        $comment = new Comment();
        $comment->product_id = $product->id;
        $comment->user_id = auth()->id(); // ユーザーID（認証されている場合）
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('product.show', ['id' => $id]);
    }

}