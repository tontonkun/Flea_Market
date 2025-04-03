@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myPage.css') }}">
@endsection

@section('content')
    <div class="upperArea">
        <div class="profileIcon">
            @if($profile && $profile->profile_image)
                <!-- ユーザーが画像をアップロードした場合 -->
                <img src="{{ asset('/' . $product->product_img_pass) }}" alt="{{ $product->product_name }}"
                    class="productImage">

            @else
                <!-- 画像が登録されていない場合 -->
                <div class="defaultProfileIcon"></div>
            @endif
        </div>
        <div class="userName">
            ユーザー名
        </div>
        <form action="/myPage/profile" method="GET">
            @csrf
            <button class="editProfile">
                プロフィールを編集
            </button>
        </form>
    </div>

    <div class="displaySelection">
        <button id="posted-sell" class="posted sold">出品した商品</button>
        <button id="posted-buy" class="posted bought">購入した商品</button>
    </div>

    <!-- 出品した商品リスト -->
    <div id="sell-products" class="product">
        <h2>出品した商品</h2>
        <div class="productList">
            @foreach($postedProducts as $product) <!-- 変数名を変更 -->
                <div class="productItem">
                    <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                    <a href="{{ route('product.showDetail', $product->id) }}">
                        <!-- 商品画像 -->
                        @if($product->product_img_pass)
                            <img src="{{ asset('/' . $product->product_img_pass) }}" alt="{{ $product->product_name }}">
                        @else
                            <div class="defaultItemImage">
                                画像なし
                            </div>
                        @endif
                    </a>
                    <div>
                        {{ $product->product_name }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 購入した商品リスト -->
    <div id="buy-products" class="product" style="display: none;">
        <h2>購入した商品</h2>
        <div class="productList">
            @foreach($purchasedProducts as $product)
                <div class="productItemArea">
                    <div class="productItem">
                        <!-- 商品画像 -->
                        @if($product->product_img_pass)
                            <img src="{{ asset('storage/product_images/' . $product->product_img_pass) }}"
                                alt="{{ $product->product_name }}">
                        @else
                            <div class="defaultItemImage">
                                画像なし
                            </div>
                        @endif
                    </div>
                    <p>{{ $product->product_name }}</p>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@section('js')
    <script>
        // 出品した商品ボタンのクリック時
        document.getElementById('posted-sell').addEventListener('click', function () {
            // 出品した商品を表示
            document.getElementById('sell-products').style.display = 'block';
            document.getElementById('buy-products').style.display = 'none';

            // 色を赤に変更
            this.style.color = 'red';
            document.getElementById('posted-buy').style.color = 'black';
        });

        // 購入した商品ボタンのクリック時
        document.getElementById('posted-buy').addEventListener('click', function () {
            // 購入した商品を表示
            document.getElementById('buy-products').style.display = 'block';
            document.getElementById('sell-products').style.display = 'none';

            // 色を赤に変更
            this.style.color = 'red';
            document.getElementById('posted-sell').style.color = 'black';
        });

        // 初期状態で「出品した商品」を表示し、「出品した商品」のボタンを赤に
        window.onload = function () {
            document.getElementById('sell-products').style.display = 'block'; // 出品した商品表示
            document.getElementById('buy-products').style.display = 'none';  // 購入した商品非表示
            document.getElementById('posted-sell').style.color = 'red'; // 出品した商品のボタン赤
            document.getElementById('posted-buy').style.color = 'black'; // 購入した商品のボタン黒
        };
    </script>

@endsection