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
                <p class="price">{{ $product->price }}</p>
                <p class="tax">（税込）</p>
            </div>

            <div class="productDescription">
                <p class="sectionTitle">商品説明</p>
                <p>{{ $product->description }}</p>
            </div>

            <div class="productInfo">
                <p class="sectionTitle">商品の情報</p>
                <div class="categoryArea">
                    <p class="subSectionTitle">カテゴリー</p>
                </div>
                <div class="conditionArea">
                    <p class="subSectionTitle">商品の状態</p>
                    <p class="condition">{{ $product->price }}</p>
                </div>
            </div>

        </div>

    </div>

@endsection