<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'evaluator_id',
        'evaluated_user_id',
        'rating_value',
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluatedUser()
    {
        return $this->belongsTo(User::class, 'evaluated_user_id');
    }
}
