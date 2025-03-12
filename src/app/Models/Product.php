<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'product_name',
        'price',
        'brand_name',
        'product_img_pass',
        'discription',
        'is_active'
    ];

    // リレーション: Userとの1対多
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // リレーション: Categoryとの1対多
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
