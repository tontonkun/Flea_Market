<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Conditionテーブルのデータを先に作成
        $this->call(ConditionSeeder::class);

        // 商品データを挿入
        $this->call(ItemSeeder::class);
    }
}
