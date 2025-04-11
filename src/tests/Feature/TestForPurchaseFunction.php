<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\PurchasedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestForPurchaseFunction extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. 購入するボタンを押下すると購入が完了する(DBテーブル処理込み)
     */
    public function test_user_can_complete_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_active' => true]);

        $this->actingAs($user);

        // 購入リクエスト送信（shipping 情報含む）
        $response = $this->post(route('purchase.process', ['item' => $item->id]), [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'shipping_building_name' => 'サンプルビル101',
        ]);

        $response->assertRedirect('/');

        $item->refresh();

        // item は非公開になっているか
        $this->assertFalse($item->is_active);

        // PurchasedItem テーブルに購入記録があるか
        $this->assertDatabaseHas('purchased_items', [
            'item_id' => $item->id,
            'purchaser_id' => $user->id,
            'item_name' => $item->name,
        ]);
    }

    /**
     * 2. 購入した商品が商品一覧画面に「sold」と表示される
     */
    public function test_purchased_item_is_marked_as_sold()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_active' => true]);

        $this->actingAs($user);

        $this->post(route('purchase.process', ['item' => $item->id]), [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'shipping_building_name' => 'テストビル',
        ]);

        auth()->logout();

        // 商品一覧に「sold」が表示されるか（テンプレート側が対応している前提）
        $response = $this->get('/');
        $response->assertSee('sold');
    }

    /**
     * 3. 購入した商品がマイページに追加されている
     */
    public function test_purchased_item_appears_in_my_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_active' => true]);

        $this->actingAs($user);

        $this->post(route('purchase.process', ['item' => $item->id]), [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都品川区テスト通り1-2-3',
            'shipping_building_name' => 'ビル名101',
        ]);

        $response = $this->get('/myPage');
        $response->assertSee($item->name);
    }
}
