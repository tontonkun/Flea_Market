<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostingController extends Controller
{
    public function showPostingPage()
    {
        $conditions = Condition::all();

        return view('postingPage', compact('conditions'));
    }

    public function postItems(Request $request)
    {
        $condition = Condition::find($request->input('condition_id'));

        // 画像の保存処理（画像がアップロードされた場合のみ）
        $imagePath = null;

        if ($request->hasFile('item_image')) {
            // 画像保存先ディレクトリ
            $directory = storage_path('app/public/item_images');

            // ディレクトリが存在しない場合に作成
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true); // 0755 はディレクトリのパーミッション
            }

            // 画像を指定したディレクトリに保存
            $imagePath = $request->file('item_image')->store('item_images', 'public');
        }

        // 商品データを保存する前に価格を半角に変換
        $price = $request->input('item_cost');
        $price = mb_convert_kana($price, 'n', 'UTF-8');

        // 新しい商品を保存
        $item = new Item();
        $item->seller_id = auth()->id();
        $item->item_name = $request->input('item_name');
        $item->price = $price;
        $item->brand_name = $request->input('brand_name') ?? null;
        $item->item_img_pass = 'storage/' . $imagePath;
        $item->save();
        $item->description = $request->input('item_description') ?? null;
        $item->is_active = true;

        // 商品の状態が設定されていれば、それも保存
        if ($condition) {
            $item->condition_id = $condition->id;
        }

        // 商品を保存
        $item->save();

        // カテゴリの保存処理
        if ($request->has('selected_category')) {
            foreach ($request->input('selected_category') as $categoryName) {
                $category = Category::firstOrCreate(['category' => $categoryName]); // 存在しないカテゴリは作成

                // 中間テーブルにカテゴリを関連付け
                ItemCategory::create([
                    'item_id' => $item->id, // 保存した後にIDを使う
                    'category_id' => $category->id,
                ]);
            }
        }

        return redirect('/')->with('success', '商品が出品されました。');
    }
}
