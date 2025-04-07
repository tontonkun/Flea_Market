<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ConditionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Condition::create(['condition' => '良好']);
        \App\Models\Condition::create(['condition' => '目立った傷や汚れなし']);
        \App\Models\Condition::create(['condition' => 'やや傷や汚れあり']);
        \App\Models\Condition::create(['condition' => '状態が悪い']);

    }
}
