<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'user_image_pass',
        'post_code',
        'address',
        'building_name',
        'profile_image',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
