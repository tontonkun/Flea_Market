<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\ItemCategory;
use App\Http\Requests\ItemPostingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostingController extends Controller
{
    public function showPostingPage()
    {
        $conditions = Condition::all();

        return view('postingPage', compact('conditions'));
    }

    public function postItems(ItemPostingRequest $request)
    {
        // 商品の状態（条件）を取得
        $condition = Condition::find($request->input('condition_id')); 

        // 画像の保存処理（画像がアップロードされた場合のみ）
        $imagePath = null;

        if ($request->hasFile('item_img_pass')) {
            $directory = storage_path('app/public/item_images');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            $imagePath = $request->file('item_img_pass')->store('item_images', 'public');
        }

        // 商品データの保存
        $price = mb_convert_kana($request->input('price'), 'n'); // 半角に変換

        if (!is_numeric($price) || $price < 1) {
            return back()->withErrors(['price' => '価格は1円以上である必要があります。'])->withInput();
        }

        $item = new Item();
        $item->seller_id = auth()->id();
        $item->item_name = $request->input('item_name');
        $item->price = $price;
        $item->brand_name = $request->input('brand_name') ?? null;
        $item->description = $request->input('description') ?? null;
        $item->item_img_pass = $imagePath ? 'storage/' . $imagePath : null;
        $item->is_active = true;

        if ($condition) {
            $item->condition_id = $condition->id;
        }

        $item->save();

        // カテゴリの保存処理
        if ($request->has('selected_category')) {
            foreach ($request->input('selected_category') as $categoryName) {
                $category = Category::firstOrCreate(['category' => $categoryName]);

                // item_category テーブルに保存
                ItemCategory::create([
                    'item_id' => $item->id,
                    'category_id' => $category->id,
                ]);
            }
        }

        return redirect('/')->with('success', '商品が出品されました。');
    }
}
