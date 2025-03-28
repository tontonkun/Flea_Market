@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/productDetail.css') }}">
@endsection

@section('content')
    <div class="separatedContent">
        <div class="imageArea">
            @if ($product->product_img_pass)
                <img src="{{ asset('storage/' . $product->product_img_pass) }}" alt="{{ $product->product_name }}"
                    class="productImage">
            @else
                <div class="defaultItemImage">No Image</div>
            @endif
        </div>

        <div class="productDetailArea">
            <div class="productName">
                {{ $product->product_name }}
            </div>
            <div class="brandArea">
                <p>ブランド名：</p>
                <p>{{ $product->brand_name }}</p>
            </div>
            <div class="priceArea">
                <p class="yen">¥</p>
                <p class="price">{{ number_format($product->price) }}</p>
                <p class="tax">（税込）</p>
            </div>

            <form action="/purchase" method="GET">
                <button class="purchaseButton">
                    購入手続きへ
                </button>
            </form>

            <div class="productDescription">
                <div class="sectionTitle">商品説明</div>
                <p>{{ $product->description }}</p>
            </div>

            <div class="productInfo">
                <div class="sectionTitle">商品の情報</div>
                <div class="categoryArea">
                    <p class="subSectionTitle">カテゴリー</p>
                    <p class="category"></p>
                </div>
                <div class="conditionArea">
                    <p class="subSectionTitle">商品の状態</p>
                    <p class="condition">{{ $product->condition->condition ?? '状態情報なし' }}</p>
                </div>
            </div>

        </div>

    </div>

@endsection