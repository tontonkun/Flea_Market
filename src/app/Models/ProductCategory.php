<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_category';

    protected $fillable = [
        'product_id',
        'category_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // 'product_id' が外部キー
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); // 'category_id' が外部キー
    }
}
