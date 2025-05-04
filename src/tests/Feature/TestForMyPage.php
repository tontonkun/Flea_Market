<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\PurchasedItem;

class TestForMyPage extends TestCase
{
    use RefreshDatabase;

    public function test_my_page_displays_profile_and_items_correctly()
    {
        // ユーザー作成＆ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // プロフィール作成
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'user_name' => 'テストユーザー',
            'user_image_pass' => 'sample.jpg',
        ]);

        // 出品商品作成
        $postedItems = Item::factory()->count(2)->create([
            'seller_id' => $user->id,
            'is_active' => true,
        ]);

        // 購入済み商品（PurchasedItemとそのItem）
        $purchasedItem = Item::factory()->create();
        $purchased = PurchasedItem::factory()->create([
            'purchaser_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);

        // 実際にアクセス
        $response = $this->get('/myPage');

        $response->assertStatus(200);

        // プロフィール情報が表示されているか
        $response->assertSee('テストユーザー');
        $response->assertSee('sample.jpg');

        // 出品商品が表示されているか
        foreach ($postedItems as $item) {
            $response->assertSee($item->item_name);
        }

        // 購入商品が表示されているか
        $response->assertSee($purchasedItem->item_name);
    }
}
