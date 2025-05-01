<!-- resources/views/purchase/stripe.blade.php -->
@extends('layouts.app')

@section('mainContents')
<div class="purchase-summary">
    <h2>購入処理</h2>
    <p>商品: {{ $item->item_name }}</p>
    <p>金額: ¥{{ number_format($item->price) }}</p>
    <form id="payment-form">
        <div id="card-element">
            <!-- Stripeカード要素 -->
        </div>
        <button id="submit" class="stripe-submit-button">支払う</button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('your-public-key-here'); // 公開キーを設定
    var clientSecret = '{{ $clientSecret }}'; // サーバーから渡されたclient_secret

    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        // 支払いを確認
        const {
            paymentIntent,
            error
        } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: card,
            }
        });

        if (error) {
            // エラー処理
            alert(error.message);
        } else {
            if (paymentIntent.status === 'succeeded') {
                // 決済成功の場合、サーバーに送信して完了処理を行う
                window.location.href = `/purchase/${{{ $item -> id }
            }
        }/complete?payment_intent=${paymentIntent.id}`;
            }
        }
    });
</script>
@endsection