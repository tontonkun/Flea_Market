@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/myPage.css') }}">
@endsection

@section('mainContents')
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
        <div class="nameAndRate">
            <div class="userName">
                {{ $profile ? $profile->user_name : 'ユーザー名未登録'  }}
            </div>
            <div class="user-rating">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $roundedRating ? 'filled-star' : 'empty-star' }}">★</span>
                    @endfor
            </div>
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
    <button id="posted-trade" class="posted trading">
        取引中の商品 <span class="item-count">{{ $totalUnreadCount }}</span>
    </button>
</div>

<!-- 出品した商品リスト -->
<div id="sell-items" class="displayArea">
    <h2>出品した商品</h2>
    <div class="itemList">
        @foreach($postedItems as $item)
        <div class="itemArea">
            <div class="itemImageContainer">
                <!-- 商品画像をクリックすると詳細ページへ遷移 -->
                <a href="{{ route('item.showDetail', $item->id) }}">
                    <!-- 商品画像 -->
                    @if($item->item_img_pass)
                    <img src="{{ asset('/' . $item->item_img_pass) }}" class="itemImage">
                    @else
                    <div class="defaultItemImage">
                        No Image
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
                <a href="{{ route('item.showDetail', $item->item->id) }}">
                    @if($item->item && $item->item->item_img_pass)
                    <img src="{{ asset('/' . $item->item->item_img_pass) }}" class="itemImage">
                    @else
                    <div class="defaultItemImage">
                        No Image
                    </div>
                    @endif
                </a>
            </div>
            <div class="itemName">{{ $item->item->item_name ?? '商品名不明' }}</div>
        </div>
        @endforeach
    </div>
</div>

<!-- 取引中の商品リスト -->
<div id="trade-items" class="displayArea" style="display: none;">
    <h2>取引中の商品</h2>
    <div class="itemList">
        @forelse($tradingItems as $item)
        <div class="itemArea">
            <div class="itemImageContainer">
                <a href="{{ route('chat.show', $item->id) }}">
                    @if($item->item_img_pass)
                    <div class="itemImageWrapper">
                        <img src="{{ asset('/' . $item->item_img_pass) }}" class="itemImage">

                        <!-- 未読メッセージ数を赤丸で表示 -->
                        @if($item->unread_count > 0)
                        <div class="unread-count">
                            {{ $item->unread_count }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="defaultItemImage">No Image</div>
                    @endif
                </a>
            </div>
            <div class="itemName">{{ $item->item_name }}</div>
        </div>
        @empty
        <p>現在、取引中の商品はありません。</p>
        @endforelse
    </div>
</div>

@endsection

@section('js')
<script>
    function showSection(showId, clickedBtnId) {
        // 全エリア非表示
        document.getElementById('sell-items').style.display = 'none';
        document.getElementById('buy-items').style.display = 'none';
        document.getElementById('trade-items').style.display = 'none';

        // 対象エリア表示
        document.getElementById(showId).style.display = 'block';

        // 全ボタンの色リセット
        document.getElementById('posted-sell').style.color = 'black';
        document.getElementById('posted-buy').style.color = 'black';
        document.getElementById('posted-trade').style.color = 'black';

        // 対象ボタンを赤に
        document.getElementById(clickedBtnId).style.color = 'red';
    }

    document.getElementById('posted-sell').addEventListener('click', function() {
        showSection('sell-items', 'posted-sell');
    });

    document.getElementById('posted-buy').addEventListener('click', function() {
        showSection('buy-items', 'posted-buy');
    });

    document.getElementById('posted-trade').addEventListener('click', function() {
        showSection('trade-items', 'posted-trade');
    });

    // 初期状態
    window.onload = function() {
        showSection('sell-items', 'posted-sell');
    };
</script>
@endsection