<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'item_id',
        'category',
    ];

    public function item()
    {
        return $this->belongsToMany(Item::class, 'item_category', 'category_id', 'item_id');
    }
}
