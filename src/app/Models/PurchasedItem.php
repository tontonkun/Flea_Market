<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedItem extends Model
{
    use HasFactory;

    protected $table = 'purchased_items';

    protected $fillable = [
        'purchaser_id',
        'item_id',
        'item_name',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building_name',
        'payment_method',
    ];

    // リレーション：購入者（ユーザー）
    public function purchaser()
    {
        return $this->belongsTo(User::class, 'purchaser_id');
    }

    // リレーション：購入された商品
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
