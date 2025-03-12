<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemPostingRequest;
use Auth;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Category;
use App\Models\ProductCategory; // 中間テーブルのモデルを追加
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostingController extends Controller
{
    public function showPostingPage()
    {
        return view('postingPage');
    }

    public function PostItems(Request $request)
    {
        // 画像の保存処理（画像がアップロードされた場合のみ）
        $imagePath = null; // 初期化

        if ($request->hasFile('profile_image')) {
            // 画像保存先ディレクトリ
            $directory = storage_path('app/public/aproduct_images');

            // ディレクトリが存在しない場合に作成
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true); // 0755 はディレクトリのパーミッション
            }

            // 画像を指定したディレクトリに保存
            $imagePath = $request->file('profile_image')->store('product_images', 'public');
        }

        // 新しい商品を保存
        $product = new Product();
        $product->user_id = auth()->id(); // 現在ログインしているユーザーIDを保存
        $product->product_name = $request->input('item_name');
        $product->price = $request->input('item_cost');
        $product->brand_name = $request->input('brand_name') ?? null;
        $product->product_img_pass = $imagePath; // 画像パス
        $product->discription = $request->input('item_description') ?? null;
        $product->is_active = true;

        // 商品の状態を設定
        $condition = Condition::where('condition', $request->input('item_state'))->first();
        if ($condition) {
            $product->condition_id = $condition->id;
        }

        // カテゴリの保存
        if ($request->has('selected_category')) {
            // selected_category の各カテゴリ名を取得して、カテゴリの ID を取得
            $categories = Category::whereIn('category', $request->input('selected_category'))->get();

            // もしカテゴリが空でない場合にのみ
            if ($categories->isNotEmpty()) {
                foreach ($categories as $category) {
                    // 中間テーブルにカテゴリを関連付け
                    $productCategory = ProductCategory::create([
                        'product_id' => $product->id,
                        'category_id' => $category->id,
                    ]);
                }
            }

            // 商品を保存
            $product->save();

            // リダイレクト先を'/'に変更し、フラッシュメッセージを設定
            return redirect('/')->with('success', '商品が出品されました。');
        }

    }
}