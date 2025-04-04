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
        <div id="recommendedItems" class="itemList">
            @foreach ($recommendedItems as $item)
                <div class="itemArea">
                    <div class="itemImageContainer">
                        <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                        <a href="{{ route('item.showDetail', $item->id) }}">
                            <!-- 商品画像 -->
                            @if ($item->item_img_pass)
                                <img src="{{ asset('/' . $item->item_img_pass) }}" class="itemImage">
                            @else
                                <div class="defaultItemImage">No Image</div>
                            @endif
                        </a>
                    </div>
                    <div class="itemName">{{ $item->item_name }}</div>
                </div>
            @endforeach
        </div>

        <!-- マイリストの商品リスト（非表示）-->
        <div id="myListItems" class="itemList" style="display: none;">
            @foreach ($favoriteItems as $favorite)
                <div class="itemArea">
                    <div class="itemImageContainer">
                        <a href="{{ route('item.showDetail', $favorite->id) }}">
                            @if ($favorite->item_img_pass)
                                <img src="{{ asset($favorite->item_img_pass) }}" class="itemImage">
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
        // DOMの読み込み完了後にスクリプトを実行
        window.addEventListener('DOMContentLoaded', function () {
            console.log("DOMが読み込まれました");

            // ボタン要素を取得
            const recommendsButton = document.getElementById('recommends');
            const myListButton = document.getElementById('myList');
            const recommendedItems = document.getElementById('recommendedItems');
            const myListItems = document.getElementById('myListItems');

            // 「おすすめ」ボタンのクリックイベント
            recommendsButton.addEventListener('click', function () {
                console.log('おすすめボタンがクリックされました');

                // おすすめ商品リストを表示
                recommendedItems.style.display = 'block';
                myListItems.style.display = 'none';

                // ボタンのアクティブ状態を変更
                recommendsButton.classList.add('active');
                myListButton.classList.remove('active');
            });

            // 「マイリスト」ボタンのクリックイベント
            myListButton.addEventListener('click', function () {
                console.log('マイリストボタンがクリックされました');

                // マイリスト商品リストを表示
                recommendedItems.style.display = 'none';
                myListItems.style.display = 'block';

                // ボタンのアクティブ状態を変更
                myListButton.classList.add('active');
                recommendsButton.classList.remove('active');
            });

            // 初期状態で「おすすめ」を選択状態にする
            if (recommendsButton && recommendedItems) {
                console.log('初期状態でおすすめを表示します');
                recommendsButton.click();  // 初期表示で「おすすめ」をクリック
            }
        });
    </script>
@endsection