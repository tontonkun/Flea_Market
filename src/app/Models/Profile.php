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
        'post_code',
        'user_image_pass',
        'address',
        'building_name',
        'profile_image',
    ];

    // プロフィールがどのユーザーに関連しているかを定義（リレーション）
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
