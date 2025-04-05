@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myPage.css') }}">
@endsection

@section('content')
    <form action="/myPage/profile" method="GET">
        </div>
        <div class="upperArea">
            <div class="profileIcon">
                @if($profile && $profile->user_image_pass)
                    <!-- ユーザーが画像をアップロードした場合 -->
                    <img src="{{ asset('storage/profile_images/' . $profile->user_image_pass) }}" class="profileIconImage">

                @else
                    <!-- 画像が登録されていない場合 -->
                    <div class="defaultProfileIcon"></div>
                @endif
            </div>
            <div class="userName">
                {{ $profile ? $profile->user_name : 'ユーザー名未登録'  }}
            </div>
            @csrf
            <button class="editProfile">
                プロフィールを編集
            </button>
        </div>
    </form>

    <div class="displaySelection">
        <button id="posted-sell" class="posted sold">出品した商品</button>
        <button id="posted-buy" class="posted bought">購入した商品</button>
    </div>

    <!-- 出品した商品リスト -->
    <div id="sell-items" class="displayArea">
        <h2>出品した商品</h2>
        <div class="itemList">
            @foreach($postedItems as $item) <!-- 変数名を変更 -->
                <div class="itemArea">
                    <div class="itemImageContainer">
                    <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                    <a href="{{ route('item.showDetail', $item->id) }}">
                        <!-- 商品画像 -->
                        @if($item->item_img_pass)
                            <img src="{{ asset('/' . $item->item_img_pass) }}" class="itemImage">
                        @else
                            <div class="defaultItemImage">
                                画像なし
                            </div>
                        @endif
                    </a>
                    </div>
                    <div class="itemName">{{ $item->item_name }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 購入した商品リスト -->
    <div id="buy-items" class="displayArea" style="display: none;">
        <h2>購入した商品</h2>
        <div class="itemList">
            @foreach($purchasedItems as $item)
                <div class="itemArea">
                    <div class="itemImageContainer">
                        <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                        <a href="{{ route('item.showDetail', $item->id) }}">
                            <!-- 商品画像 -->
                            @if($item->item_img_pass)
                                <img src="{{ asset('/' . $item->item_img_pass) }}" class="itemImage">
                            @else
                                <div class="defaultItemImage">
                                    画像なし
                                </div>
                            @endif
                        </a>
                        </div>
                        <div class="itemName">{{ $item->item_name }}</div>
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
            document.getElementById('sell-items').style.display = 'block';
            document.getElementById('buy-items').style.display = 'none';

            // 色を赤に変更
            this.style.color = 'red';
            document.getElementById('posted-buy').style.color = 'black';
        });

        // 購入した商品ボタンのクリック時
        document.getElementById('posted-buy').addEventListener('click', function () {
            // 購入した商品を表示
            document.getElementById('buy-items').style.display = 'block';
            document.getElementById('sell-items').style.display = 'none';

            // 色を赤に変更
            this.style.color = 'red';
            document.getElementById('posted-sell').style.color = 'black';
        });

        // 初期状態で「出品した商品」を表示し、「出品した商品」のボタンを赤に
        window.onload = function () {
            document.getElementById('sell-items').style.display = 'block'; // 出品した商品表示
            document.getElementById('buy-items').style.display = 'none';  // 購入した商品非表示
            document.getElementById('posted-sell').style.color = 'red'; // 出品した商品のボタン赤
            document.getElementById('posted-buy').style.color = 'black'; // 購入した商品のボタン黒
        };
    </script>

@endsection