@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('mainContents')

<div id="stripe-config" data-stripe-public-key="{{ config('services.stripe.public') }}"></div>

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

        <form id="paymentForm" action="{{ route('purchase.updatePayment') }}" method="POST">
            @csrf
            <div class="paymentSelectionArea">
                <div class="sectionTitle">支払い方法</div>
                <select name="payment_method" class="paymentSelect" required data-selected="{{ session('payment_method_selected') ?? '' }}">
                    <option value="" disabled {{ session('payment_method_selected') ? '' : 'selected' }}>選択してください</option>
                    <option value="convenience_store" {{ session('payment_method_selected') == 'convenience_store' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="credit_card" {{ session('payment_method_selected') == 'credit_card' ? 'selected' : '' }}>カード決済</option>
                </select>
            </div>
        </form>

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

            @php
            $tempAddress = session('temporary_address');
            @endphp

            <div class="addressInfo">
                <p><strong>〒 </strong> {{ $tempAddress['postal_code'] ?? $profile->postal_code ?? '未登録' }}</p>
                <div class="addressAndBuilding">
                    <p>{{ $tempAddress['address'] ?? $profile->address ?? '未登録' }}</p>
                    <p>　</p>
                    <p>{{ $tempAddress['building_name'] ?? $profile->building_name ?? '未登録' }}</p>
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
                <!-- <p id="paymentMethodDisplay">未選択</p> -->
                <p id="paymentMethodDisplay"> {{ session('payment_method_display') ?? '未選択' }}</p>
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

<script src="https://js.stripe.com/v3/"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.querySelector('select[name="payment_method"]');
        const paymentDisplay = document.getElementById('paymentMethodDisplay');
        const hiddenInput = document.getElementById('paymentMethodHidden');
        const submitButton = document.querySelector('.finalPurchaseButton');
        const creditCardForm = document.getElementById('creditCardForm');

        const selectedValue = paymentSelect.getAttribute('data-selected');

        if (selectedValue) {
            hiddenInput.value = selectedValue;
            submitButton.disabled = false;
            paymentSelect.classList.add('selected');
        } else {
            submitButton.disabled = true;
        }

        paymentSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const selectedText = this.options[this.selectedIndex].text;

            paymentDisplay.textContent = selectedText;
            hiddenInput.value = selectedValue;
            submitButton.disabled = !selectedValue;

            if (selectedValue === 'credit_card') {
                creditCardForm.style.display = 'block';
            } else {
                creditCardForm.style.display = 'none';
            }

            if (selectedValue) {
                paymentSelect.classList.add('selected');
            } else {
                paymentSelect.classList.remove('selected');
            }
        });
    });

    var stripePublicKey = document.getElementById('stripe-config').getAttribute('data-stripe-public-key');
    var stripe = Stripe(stripePublicKey);
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var paymentForm = document.getElementById('paymentForm');
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                var tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'stripeToken';
                tokenInput.value = result.token.id;
                paymentForm.appendChild(tokenInput);

                paymentForm.submit();
            }
        });
    });
</script>

@endsection