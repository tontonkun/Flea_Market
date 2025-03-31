@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/productDetail.css') }}">
@endsection

@section('content')
    <div class="separatedContent">
        <div class="imageArea">
            @if ($product->product_img_pass)
                <img src="{{ asset('/' . $product->product_img_pass) }}" alt="{{ $product->product_name }}"
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

            <div class="iconArea">
                <div class="favoriteIconArea">
                    <form action="{{ route('product.addFavorite', ['id' => $product->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="favoriteIcon">☆</button>
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
                    @foreach ($product->category as $category)
                        <p class="category">{{ $category->category }}</p>
                    @endforeach
                </div>
                <div class="conditionArea">
                    <p class="subSectionTitle">商品の状態</p>
                    <p class="condition">{{ $product->condition->condition ?? '状態情報なし' }}</p>
                </div>
            </div>

            <div class="commentInputArea">
                <span class="commentInputAreaTitle">コメント(</span>
                <span class="commentCountInInputArea">
                    {{ $commentCount }}
                </span>
                <span class="commentInputAreaTitle">)</span>

                <div class="profileIcon">
                    @if($profile && $profile->profile_image)
                        <!-- ユーザーが画像をアップロードした場合 -->
                        <img src="{{ asset('/' . $profile->profile_image) }}" alt="Profile Icon" class="profileIconImage">
                    @else
                        <!-- 画像が登録されていない場合 -->
                        <div class="defaultProfileIcon"></div>
                    @endif
                </div>
                <div class="userName">
                    {{ $profile ? $profile->user_name : 'ユーザー名未登録'  }}
                </div>
                <div class="usercomment">
                    @foreach ($product->comments as $comment)
                        <div class="comment">
                            <p>{{ $comment->comment ?? '' }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="inputAreaDescription">
                    商品へのコメント
                </div>
                <div class="inputArea">
                    <form action="{{ route('product.addComment', ['id' => $product->id]) }}" method="POST">
                        @csrf
                        <textarea class="inputArea" name="comment" rows="4" placeholder="商品へのコメントを入力してください..."
                            required></textarea>
                        <button type="submit" class="submitCommentButton">
                            コメントを送信する
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection