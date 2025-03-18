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
        // 商品状態のリストを取得
        $conditions = Condition::all();

        return view('postingPage', compact('conditions'));
    }

    public function PostItems(Request $request)
    {
        // 商品の状態を設定（先に取得してから使用）
        //$condition = Condition::where('condition', $request->input('item_state'))->first();
        $condition = Condition::find($request->input('condition_id'));

        // 画像の保存処理（画像がアップロードされた場合のみ）
        $imagePath = null; // 初期化

        if ($request->hasFile('product_image')) {
            // 画像保存先ディレクトリ
            $directory = storage_path('app/public/product_images');

            // ディレクトリが存在しない場合に作成
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true); // 0755 はディレクトリのパーミッション
            }

            // 画像を指定したディレクトリに保存
            $imagePath = $request->file('product_image')->store('product_images', 'public');
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

        // 商品の状態が設定されていれば、それも保存
        if ($condition) {
            $product->condition_id = $condition->id; // 状態IDを設定
        }

        // 商品を保存（ここで保存）
        $product->save();

        // カテゴリの保存処理
        if ($request->has('selected_category')) {
            foreach ($request->input('selected_category') as $categoryName) {
                $category = Category::firstOrCreate(['category' => $categoryName]); // 存在しないカテゴリは作成

                // 中間テーブルにカテゴリを関連付け
                ProductCategory::create([
                    'product_id' => $product->id, // 保存した後にIDを使う
                    'category_id' => $category->id,
                ]);
            }
        }

        // '/'にリダイレクトし、フラッシュメッセージを設定
        return redirect('/')->with('success', '商品が出品されました。');
    }

}
