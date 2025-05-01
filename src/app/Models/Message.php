<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'message',
        'image_path',
        'read_at',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 必要に応じてアイテムともリレーションを定義
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
