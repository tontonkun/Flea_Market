<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'product_id',
        'category',
    ];

    // リレーション
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
