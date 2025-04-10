@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="entire">
        <div class="pageLeftSide">
            <div class="itemDescriptionArea">
                <div class="itemImageArea">
                    @if ($item->item_img_pass)
                        <img class="itemImage" src="{{ asset('/' . $item->item_img_pass) }}" alt="{{ $item->item_name }}">
                    @else
                        <div class="defaultItemImage">No Image</div>
                    @endif
                </div>
                <div class="itemInfoArea">
                    <div class="itemName">{{ $item->item_name }}</div>
                    <div class="itemCost">
                        <p>￥</p>
                        <p>{{ number_format($item->price) }}</p>
                    </div>
                </div>
            </div>

            <div class="paymentSelectionArea">
                <div class="sectionTitle">支払い方法</div>
                <select name="payment_method" class="paymentSelect" required>
                    <option value="" disabled selected>選択してください</option>
                    <option value="convenience_store">コンビニ払い</option>
                    <option value="credit_card">カード決済</option>
                </select>
            </div>

            <div id="creditCardForm" style="display:none;">
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>

            <div class="deliveryInfoArea">
                <div class="titleAndButtonArea">
                    <div class="sectionTitle">配送先</div>
                    <form action="/purchase/address/{{ $item->id }}" method="GET">
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
                    <p>￥{{ number_format($item->price) }}</p>
                </div>
                <div class="lowerArea">
                    <p>支払方法</p>
                    <p id="paymentMethodDisplay">未選択</p>
                </div>
            </div>

            <form id="purchaseForm" action="{{ route('purchase.process', ['item' => $item->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" id="paymentMethodHidden">
                <button type="submit" class="finalPurchaseButton" disabled>
                    購入する
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentSelect = document.querySelector('select[name="payment_method"]');
            const paymentDisplay = document.getElementById('paymentMethodDisplay');
            const submitButton = document.querySelector('.finalPurchaseButton');
            const hiddenInput = document.getElementById('paymentMethodHidden');

            paymentSelect.addEventListener('change', function () {
                const selectedMethod = paymentSelect.options[paymentSelect.selectedIndex].text;
                const selectedValue = paymentSelect.value;

                if (selectedValue === "") {
                    paymentDisplay.textContent = "支払方法を選択してください";
                    submitButton.disabled = true;
                } else {
                    paymentDisplay.textContent = selectedMethod;
                    hiddenInput.value = selectedValue;
                    submitButton.disabled = false;
                }
            });
        });
    </script>
@endsection
