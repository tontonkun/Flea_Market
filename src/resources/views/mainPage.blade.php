@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mainPage.css') }}">
@endsection

{{-- フラッシュメッセージの表示 --}}
@if(session('success'))
    <div class="postingAnnounce">
        {{ session('success') }}
    </div>
@endif

@section('content')
    <div class="displaySelectionArea">
        <button id="recommends" class="displaySelection active">おすすめ</button>
        <button id="myList" class="displaySelection">マイリスト</button>
    </div>

    <div class="displayArea">
        <!-- おすすめ商品リスト -->
        <div id="recommendedProducts" class="productList">
            @foreach ($recommendedProducts as $product)
                <div class="productItem">
                    <div class="productImageContainer">
                        <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                        <a href="{{ route('product.showDetail', $product->id) }}">
                            <!-- 商品画像 -->
                            @if ($product->product_img_pass)
                                <img src="{{ asset('/' . $product->product_img_pass) }}" class="productImage">
                            @else
                                <div class="defaultItemImage">No Image</div>
                            @endif
                        </a>
                    </div>
                    <div class="productName">{{ $product->product_name }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- マイリストの商品リスト（非表示）-->
    <div id="myListProducts" class="productList" style="display: none;">
        @foreach ($favoriteProducts as $favorite)
            <div class="productItem">
                <div class="productImageContainer">
                    <a href="{{ route('product.showDetail', $favorite->product->id) }}">
                        @if ($favorite->product->product_img_pass)
                            <img src="{{ asset('/' . $favorite->product->product_img_pass) }}" class="productImage">
                        @else
                            <div class="defaultItemImage">No Image</div>
                        @endif
                    </a>
                </div>
                <div class="productName">{{ $favorite->product->product_name }}</div>
            </div>
        @endforeach
    </div>
@endsection

@section('js')
    <script>
        // 「おすすめ」ボタンがクリックされたとき
        document.getElementById('recommends').addEventListener('click', function () {
            document.getElementById('recommendedProducts').style.display = 'block';
            document.getElementById('myListProducts').style.display = 'none';
            document.getElementById('recommends').classList.add('active');
            document.getElementById('myList').classList.remove('active');
        });

        // 「マイリスト」ボタンがクリックされたとき
        document.getElementById('myList').addEventListener('click', function () {
            document.getElementById('myListProducts').style.display = 'block';
            document.getElementById('recommendedProducts').style.display = 'none';
            document.getElementById('myList').classList.add('active');
            document.getElementById('recommends').classList.remove('active');
        });
    </script>
@endsection