<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function build()
    {
        return $this->subject('取引完了のお知らせ')
            ->view('emails.notificationMail') // ビュー名を修正
            ->with([
                'itemName' => $this->item->item_name,
                'itemPrice' => number_format($this->item->price),
                'sellerName' => $this->item->seller->name,
            ]);
    }
}
