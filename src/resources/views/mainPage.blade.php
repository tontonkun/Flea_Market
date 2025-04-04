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
    </div>

    <!-- マイリストの商品リスト（非表示）-->
    <div id="myListItems" class="itemList" style="display: none;">
        @foreach ($favoriteItems as $favorite)
            <!-- itemが存在する場合のみ表示 -->
            @if ($favorite->item)
                <div class="itemItem">
                    <div class="itemImageContainer">
                        <a href="{{ route('item.showDetail', $favorite->item->id) }}">
                            @if ($favorite->item->item_img_pass)
                                <img src="{{ asset('/' . $favorite->item->item_img_pass) }}" class="itemImage">
                            @else
                                <div class="defaultItemImage">No Image</div>
                            @endif
                        </a>
                    </div>
                    <div class="itemName">{{ $favorite->item->item_name }}</div>
                </div>
            @endif
        @endforeach
    </div>

@endsection

