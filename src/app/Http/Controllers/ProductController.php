<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showDetail($id)
    {
        // 商品IDに基づいて商品の詳細情報を取得
        $product = Product::findOrFail($id);

        // 商品詳細ページを表示
        return view('productDetail', compact('product'));
    }
}