@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/itemDetail.css') }}">
@endsection

@section('mainContents')
<div class="separatedContent">
    <div class="imageArea {{ !$item->is_active ? 'sold-out' : '' }}">
        @if ($item->item_img_pass)
        <img class="itemImage" src="{{ asset('/' . $item->item_img_pass) }}" alt="{{ $item->item_name }}">
        @if (!$item->is_active)
        <div class="sold-label">Sold</div>
        @endif
        @else
        <div class="defaultItemImage">No Image</div>
        @endif
    </div>

    <div class="itemDetailArea">
        <div class="itemName">
            {{ $item->item_name }}
        </div>
        <div class="brandArea">
            <p>ブランド名：</p>
            <p>{{ $item->brand_name }}</p>
        </div>
        <div class="priceArea">
            <p class="yen">¥</p>
            <p class="price">{{ number_format($item->price) }}</p>
            <p class="tax">（税込）</p>
        </div>

        <div class="iconArea">
            <div class="favoriteIconArea">
                <form action="{{ route('item.addFavorite', ['id' => $item->id]) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="favoriteIcon {{ auth()->check() && auth()->user()->favorites()->where('item_id', $item->id)->exists() ? 'active' : '' }}">
                        ★
                    </button>
                </form>
                <span class="favoriteCount">
                    {{ $favoriteCount }}
                </span>
            </div>

            <div class="commentIconArea">
                <div class="commentIcon">💬</div>
                <span class="commentCount">
                    {{ $commentCount }}
                </span>
            </div>
        </div>

        <form action="{{ route('showPurchasePage', ['item' => $item->id]) }}" method="GET">
            <button class="purchaseButton"
                type="submit"
                {{ !$item->is_active ? 'disabled' : '' }}>
                購入手続きへ
            </button>
        </form>

        <div class="itemDescription">
            <div class="sectionTitle">商品説明</div>
            <p>{{ $item->description }}</p>
        </div>

        <div class="itemInfo">
            <div class="sectionTitle">商品の情報</div>
            <div class="categoryArea">
                <p class="subSectionTitle">カテゴリー</p>
                @foreach ($item->category as $category)
                <p class="category">{{ $category->category }}</p>
                @endforeach
            </div>
            <div class="conditionArea">
                <p class="subSectionTitle">商品の状態</p>
                <p class="condition">{{ $item->condition->condition ?? '状態情報なし' }}</p>
            </div>
        </div>

        <div class="commentInputArea">
            <span class="commentInputAreaTitle">コメント(</span>
            <span class="commentCountInInputArea">
                {{ $commentCount }}
            </span>
            <span class="commentInputAreaTitle">)</span>

            <div class="usercomment">
                @foreach ($item->comments as $comment)
                <div class="profileIcon">
                    @if($comment->user && $comment->user->profile && $comment->user->profile->user_image_pass)
                    <img src="{{ asset('storage/profile_images/' . $comment->user->profile->user_image_pass) }}"
                        class="profileIconImage">
                    @else
                    <!-- 画像が登録されていない場合 -->
                    <div class="defaultProfileIcon"></div>
                    @endif
                    <div class="userName">
                        {{ $comment->user->profile ? $comment->user->profile->user_name : 'ユーザー名未登録' }}
                    </div>
                </div>

                <div class="commentArea">
                    <div class="comment">{{ $comment->comment ?? '' }}</div>
                </div>
                @endforeach
            </div>

            <div class="inputAreaDescription">
                商品へのコメント
            </div>
            <div class="inputArea">
                <form action="{{ route('item.addComment', ['id' => $item->id])}}" method="POST">
                    @csrf
                    <textarea class="inputArea" name="comment" rows="4" placeholder="商品へのコメントを入力してください...">{{ old('comment') }}</textarea>

                    <button type="submit" class="submitCommentButton">
                        コメントを送信する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection