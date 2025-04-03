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
                    <div class="productCost">@extends('layouts.app')

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
                                    <div class="sectionTitle">
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
                                        <form action="/purchase/address/{{ $product->id }}" method="GET">
                                            <button type="submit" class="addressChange">変更する</button>
                                        </form>
                                    </div>
                                    <div class="addressInfo">
                                        @if ($profile && $profile->post_code)
                                            <p><strong>〒 </strong> {{ $profile->post_code }}</p>
                                        @else
                                            <p>郵便番号：未登録</p>
                                        @endif
                                        <div class="addressAndBuilding">
                                            @if ($profile && $profile->address)
                                                <p>{{ $profile->address }}</p>
                                            @else
                                                <p>住所：未登録</p>
                                            @endif
                                            <p>&nbsp;</p>
                                            @if ($profile && $profile->building_name)
                                                <p>{{ $profile->building_name }}</p>
                                            @else
                                                <p>建物名：未登録</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="pageRightSide">
                                <div class="summaryBox">
                                    <div class="upperArea">
                                        <p>商品代金</p>
                                        <p>￥{{ number_format($product->price) }}</p>
                                    </div>
                                    <div class="lowerArea">
                                        <p>支払方法</p>
                                        <p id="paymentMethodDisplay">支払方法を選択してください</p>
                                    </div>
                                </div>
                                <!-- 支払い方法選択フォーム -->
                                <form id="purchaseForm" action="/purchase/{{ $product->id }}/process" method="POST">
                                    @csrf
                                    <button type="submit" class="finalPurchaseButton">
                                        購入する
                                    </button>
                                </form>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const paymentSelect = document.querySelector('select[name="payment_method"]'); // 支払い方法のプルダウン
                                const paymentDisplay = document.getElementById('paymentMethodDisplay'); // 支払い方法を表示する<p>
                                const purchaseForm = document.getElementById('purchaseForm'); // 購入フォーム

                                // プルダウンで選択が変更されたとき
                                paymentSelect.addEventListener('change', function () {
                                    const selectedMethod = paymentSelect.options[paymentSelect.selectedIndex].text; // 選ばれた支払い方法
                                    if (selectedMethod === "選択してください") {
                                        paymentDisplay.textContent = "支払方法を選択してください"; // 選択されていない場合のメッセージ
                                    } else {
                                        paymentDisplay.textContent = selectedMethod; // 支払い方法を表示
                                    }

                                    // クレジットカード選択の場合、Stripe Checkoutを呼び出す
                                    if (selectedMethod === "カード決済") {
                                        // StripeのCheckoutセッションを作成してリダイレクト
                                        purchaseForm.action = '/purchase/{{ $product->id }}/create-checkout-session'; // Stripeセッション用のルートに変更
                                    } else {
                                        purchaseForm.action = '/purchase/{{ $product->id }}/process'; // 通常の購入処理に戻す
                                    }
                                });
                            });
                        </script>
                    @endsection

                        <p>￥</p>
                        <p>
                            {{ number_format($product->price) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="paymentSelectionArea">
                <div class="sectionTitle">
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
                    <form action="/purchase/address/{{ $product->id }}" method="GET">
                        <button type="submit" class="addressChange">変更する</button>
                    </form>
                </div>
                <div class="addressInfo">
                    @if ($profile && $profile->post_code)
                        <p><strong>〒 </strong> {{ $profile->post_code }}</p>
                    @else
                        <p>郵便番号：未登録</p>
                    @endif
                    <div class="addressAndBuilding">
                        @if ($profile && $profile->address)
                            <p>{{ $profile->address }}</p>
                        @else
                            <p>住所：未登録</p>
                        @endif
                        <p>&nbsp;</p>
                        @if ($profile && $profile->building_name)
                            <p>{{ $profile->building_name }}</p>
                        @else
                            <p>建物名：未登録</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="pageRightSide">
            <div class="summaryBox">
                <div class="upperArea">
                    <p>商品代金</p>
                    <p>￥{{ number_format($product->price) }}</p>
                </div>
                <div class="lowerArea">
                    <p>支払方法</p>
                    <p id="paymentMethodDisplay">支払方法を選択してください</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentSelect = document.querySelector('select[name="payment_method"]'); // 支払い方法のプルダウン
            const paymentDisplay = document.getElementById('paymentMethodDisplay'); // 支払い方法を表示する<p>

            // プルダウンで選択が変更されたとき
            paymentSelect.addEventListener('change', function () {
                const selectedMethod = paymentSelect.options[paymentSelect.selectedIndex].text; // 選ばれた支払い方法
                if (selectedMethod === "選択してください") {
                    paymentDisplay.textContent = "支払方法を選択してください"; // 選択されていない場合のメッセージ
                } else {
                    paymentDisplay.textContent = selectedMethod; // 支払い方法を表示
                }
            });
        });
    </script>
@endsection