<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>取引完了のお知らせ</title>
</head>

<body>
    <p>{{ $item->seller->name }} 様</p>

    <p>以下の商品について取引が完了しましたので、お知らせいたします。</p>

    <hr>
    <p><strong>商品名：</strong> {{ $item->item_name }}</p>
    <p><strong>購入者：</strong> {{ $item->purchasedItem->purchaser->name ?? '不明' }}</p>
    <hr>

    <p>このたびはご出品いただきありがとうございました。</p>

    <p>マイページ内の「取引中の商品」で該当商品をクリックし、購入者様の評価もお願いいたします。</p>

    <p>引き続き当サービスをよろしくお願いいたします。</p>

    <br>
    <p>---</p>
    <p>{{ config('app.name') }} 運営チーム</p>
</body>

</html>