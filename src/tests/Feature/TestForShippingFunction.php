<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\PurchasedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TestForShippingFunction extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. 登録した住所がpurchaseのViewに反映されている
     *
     * @return void
     */
    public function test_address_is_displayed_on_purchase_page()
    {
        // ダミーユーザーを作成し、ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // ダミーアイテムを作成
        $item = Item::factory()->create([
            'item_name' => 'Test Item',
            'price' => 1000,
        ]);

        // プロフィールと住所を作成
        $profile = Profile::create([
            'user_id' => $user->id,
            'user_name' => 'Test User',
            'postal_code' => '123-4567',
            'address' => 'Test Address, City, Country',
            'building_name' => 'Test Building',
        ]);

        // 購入ページにアクセス
        $response = $this->get(route('showPurchasePage', ['item' => $item->id]));

        // 住所がページに反映されているかテスト
        $response->assertSee($profile->postal_code);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building_name);
    }

    /**
     * 2. 購入した商品に送付先住所が紐づいて登録される
     *
     * @return void
     */
    public function test_shipping_address_is_associated_with_purchased_item()
{
    // ダミーユーザーを作成し、ログイン
    $user = User::factory()->create();
    $this->actingAs($user);

    // ダミーアイテムを作成
    $item = Item::factory()->create([
        'item_name' => 'Test Item',
        'price' => 1000,
        'is_active' => true, // 忘れずに！
    ]);

    // プロフィールを作成（セッションに入れない場合用）
    Profile::create([
        'user_id' => $user->id,
        'user_name' => 'Test User',
        'postal_code' => '123-4567',
        'address' => 'Test Address, City, Country',
        'building_name' => 'Test Building',
    ]);

    // 購入処理をセッション付きで送信
    $response = $this->withSession([
        'temporary_address' => [
            'postal_code' => '123-4567',
            'address' => 'Test Address, City, Country',
            'building_name' => 'Test Building',
        ]
    ])->post(route('purchase.process', ['item' => $item->id]), [
        'payment_method' => 'credit_card',
    ]);

    // 購入した商品がデータベースに正しく保存されているか確認
    $this->assertDatabaseHas('purchased_items', [
        'purchaser_id' => $user->id,
        'item_id' => $item->id,
        'shipping_postal_code' => '123-4567',
        'shipping_address' => 'Test Address, City, Country',
        'shipping_building_name' => 'Test Building',
        'payment_method' => 'credit_card',
    ]);
}

}
