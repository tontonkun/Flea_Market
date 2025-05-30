@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mainPage.css') }}">
@endsection

@section('mainContents')
<!-- メッセージ表示エリア -->
<div id="loginMessage" style="display: none; color: red; padding: 10px; border: 1px solid red;">
    商品のお気に入り登録、およびマイリスト表示はログイン後に実施可能です
</div>

<!-- 非表示ボタンにログイン状態を埋め込む -->
<button id="myButton" data-logged-in="{{ auth()->check() ? 'true' : 'false' }}" style="display: none;">ボタン</button>

<div class="displaySelectionArea">
    <button id="recommends" class="displaySelection active">おすすめ</button>
    <button id="myList" class="displaySelection">マイリスト</button>
</div>

<div class="displayArea">
    <!-- おすすめ商品リスト -->
    <div id="recommendedItems" class="itemList">
        @foreach ($recommendedItems as $item)
        <div class="itemArea">
            <div class="itemImageContainer {{ !$item->is_active ? 'sold-out' : '' }}">
                <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                <a href="{{ route('item.showDetail', $item->id) }}">
                    <!-- 商品画像 -->
                    @if ($item->item_img_pass)
                    <img src="{{ asset(urldecode($item->item_img_pass)) }}" class="itemImage"
                        onerror="this.style.display='none'; this.previousElementSibling.style.display='block';">
                    @if (!$item->is_active)
                    <div class="sold-label">Sold</div>
                    @endif
                    @else
                    <div class="defaultItemImage" style="display: block;">No Image</div>
                    @endif
                </a>
            </div>
            <div class="itemName">{{ $item->item_name }}</div>
        </div>
        @endforeach
    </div>

    <!-- マイリストの商品リスト（非表示）-->
    <div id="myListItems" class="itemList" hidden>
        @foreach ($favoriteItems as $favorite)
        <div class="itemArea">
            <div class="itemImageContainer {{ !$favorite->is_active ? 'sold-out' : '' }}">
                <a href="{{ route('item.showDetail', $favorite->id) }}">
                    <!-- 商品画像 -->
                    @if ($favorite->item_img_pass)
                    <img src="{{ asset($favorite->item_img_pass) }}" class="itemImage">
                    @if (!$favorite->is_active)
                    <div class="sold-label">Sold</div>
                    @endif
                    @else
                    <div class="defaultItemImage">No Image</div>
                    @endif
                </a>
            </div>
            <div class="itemName">{{ $favorite->item_name }}</div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@section('js')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        const recommendsButton = document.getElementById('recommends');
        const myListButton = document.getElementById('myList');
        const recommendedItems = document.getElementById('recommendedItems');
        const myListItems = document.getElementById('myListItems');
        const loginMessage = document.getElementById('loginMessage');

        // isUserLoggedIn 変数でログイン状態を判定
        const isUserLoggedIn = document.getElementById('myButton').getAttribute('data-logged-in') === 'true';

        // おすすめボタン
        recommendsButton.addEventListener('click', function() {
            recommendedItems.style.display = 'block';
            myListItems.style.display = 'none';
            recommendsButton.classList.add('active');
            myListButton.classList.remove('active');

            if (loginMessage) loginMessage.style.display = 'none';
        });

        // マイリストボタン
        myListButton.addEventListener('click', function() {

            if (!isUserLoggedIn) {
                if (loginMessage) loginMessage.style.display = 'block';
                return;
            }

            recommendedItems.style.display = 'none';
            myListItems.style.display = 'block';
            myListButton.classList.add('active');
            recommendsButton.classList.remove('active');
            if (loginMessage) loginMessage.style.display = 'none';
        });

        // 初期状態で「おすすめ」を選択状態にする
        if (recommendsButton && recommendedItems) {
            recommendsButton.click();
        }
    });
</script>
@endsection