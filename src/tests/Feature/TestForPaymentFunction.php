<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestForPaymentFunction extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_selection_reflects_in_display()
    {
        // ユーザー・アイテム作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'price' => 1000,
        ]);

        // プロフィールも必要なら用意
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト',
            'building_name' => 'テストビル',
        ]);

        // セッションに支払方法を保存してアクセス
        $response = $this
            ->actingAs($user)
            ->withSession([
                'payment_method_display' => 'コンビニ払い',
                'payment_method_selected' => 'convenience_store',
            ])
            ->get(route('showPurchasePage', ['item' => $item->id]));

        // 表示されていることを確認
        $response->assertStatus(200);
        $response->assertSeeText('コンビニ払い');
    }
}
