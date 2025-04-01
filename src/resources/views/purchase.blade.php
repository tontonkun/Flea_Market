@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="entire">
        <div class="pageLeftSide">
            <div class="productDescriptionArea">
                <div class="productImageArea">
                    @if ($product->product_img_pass)
                        <img class="productImage" src="{{ asset('/' . $product->product_img_pass) }}"
                            alt="{{ $product->product_name }}">
                    @else
                        <div class="defaultItemImage">No Image</div>
                    @endif
                </div>
                <div class="productInfoArea">
                    <div class="productName">
                        {{ $product->product_name }}
                    </div>
                    <div class="productCost">
                        <p>￥</p>
                        <p>
                            {{ number_format($product->price) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="paymentSelectionArea">
                <div class="sectiontitle">
                    支払い方法
                </div>
                <select name="payment_method" class="paymentSelect" required>
                    <option value="" disabled selected>選択してください</option>
                    <option value="convenience_store">コンビニ払い</option>
                    <option value="credit_card">カード決済</option>
                </select>
            </div>


            <div class="deliveryInfoArea">
                <div class="titleAndButtonArea">
                    <div class="sectionTitle">配送先</div>
                    <button class="addressChange">変更する</button>
                </div>
                <div class="addressInfo">
                    @if ($profile && $profile->post_code)
                        <p><strong>〒 </strong> {{ $profile->post_code }}</p>
                    @else
                        <p>郵便番号：なし</p>
                    @endif
                </div>
            </div>

        </div>

        <div class="pageRightSide">
            <div class="summaryBox">
                <div class="upperArea">
                    <p>商品代金</p>
                    <p>￥</p>
                </div>
                <div class="lowerArea">
                    <p>支払方法</p>
                    <p>コンビニ払い</p>
                </div>
            </div>
            <form action="/purchase/{{ $product->id }}/process" method="POST">
                @csrf
                <button type="submit" class="finalPurchaseButton">
                    購入する
                </button>
            </form>
        </div>
    </div>
@endsection