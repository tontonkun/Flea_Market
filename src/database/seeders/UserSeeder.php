<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 出品者1
        User::factory()->create([
            'name' => '出品者1',
            'email' => 'seller1@example.com',
        ]);

        // 出品者2
        User::factory()->create([
            'name' => '出品者2',
            'email' => 'seller2@example.com',
        ]);

        // 商品を出品しない見る専ユーザー
        User::factory()->create([
            'name' => '見る専ユーザー',
            'email' => 'viewer@example.com',
        ]);
    }
}
