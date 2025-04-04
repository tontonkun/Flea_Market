<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_category';

    protected $fillable = [
        'item_id',
        'category_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id'); // 'item_id' が外部キー
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); // 'category_id' が外部キー
    }
}
