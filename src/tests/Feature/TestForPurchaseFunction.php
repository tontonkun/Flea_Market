<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\PurchasedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TestForPurchaseFunction extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. 購入するボタンを押下すると購入が完了する(DBテーブル処理込み)
     */
    public function test_user_can_complete_purchase()
    {
        $user = \App\Models\User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create([
            'item_name' => 'サンプルアイテム',
            'price' => 1000,
            'is_active' => true, // アイテムが購入可能な状態
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // 一時的な住所をセッションに設定
        session(['temporary_address' => [
            'postal_code' => '123-4567',
            'address' => '住所の一部、都市名',
            'building_name' => 'ビル名',
        ]]);

        // 支払い方法を選択
        $response = $this->post(route('purchase.updatePayment'), [
            'payment_method' => 'credit_card',
        ]);

        // セッションに支払い方法が正しく保存されているか確認
        $this->assertEquals('カード決済', session('payment_method_display'));
        $this->assertEquals('credit_card', session('payment_method_selected'));

        // 購入処理を実行（購入フォームを送信）
        $response = $this->post(route('purchase.process', ['item' => $item->id]), [
            'payment_method' => 'credit_card',  // 選択された支払い方法を送信
        ]);

        // 購入が成功したことを確認
        $response->assertRedirect('/');
        $response->assertSessionHas('success', '商品を購入しました');

        // 購入した商品が PurchasedItem テーブルに保存されているか確認
        $this->assertDatabaseHas('purchased_items', [
            'purchaser_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'credit_card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '住所の一部、都市名',
            'shipping_building_name' => 'ビル名',
        ]);

        // アイテムが購入後に非アクティブ（購入済み）になったか確認
        $item->refresh();
        $this->assertFalse($item->is_active);
    }

    /**
     * 2. 購入した商品が商品一覧画面に「sold」と表示される
     */
    public function test_sold_label_is_displayed_for_sold_items()
    {
        // ユーザーを作成
        $user = \App\Models\User::factory()->create();

        // 購入済みのアイテムを作成
        $item = Item::factory()->create([
            'item_name' => 'サンプルアイテム',
            'price' => 1000,
            'is_active' => false,  // 購入済みアイテム
        ]);

        // メインページをリクエスト
        $response = $this->get('/'); // ルート '/' は MainPageController の showMainPage に対応

        // レスポンスに 'Sold' ラベルが含まれていることを確認
        $response->assertSee('<div class="sold-label">Sold</div>', false);

        // アイテムが売り切れ（is_activeがfalse）の場合、sold-outクラスが適切に表示されていることも確認
        $response->assertSee('<div class="itemImageContainer sold-out">', false);
    }


    /**
     * 3. 購入した商品がマイページに追加されている
     */
    public function test_purchased_item_appears_in_my_page()
    {
    // テスト用のユーザーを作成
        $user = \App\Models\User::factory()->create();
        
        // ユーザーでログイン
        $this->actingAs($user);

        // 商品を作成
        $item = Item::factory()->create([
            'item_name' => 'テストアイテム',
            'price' => 1000,
            'is_active' => true,
        ]);

        // ユーザーが商品を購入
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        
        // 購入した商品を購入済みとして登録
        $purchasedItem = PurchasedItem::create([
            'purchaser_id' => $user->id,
            'item_id' => $item->id,
            'item_name' => $item->item_name, 
            'payment_method' => 'credit_card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '住所の一部、都市名',
            'shipping_building_name' => 'ビル名',
        ]);

        // 購入後のページにアクセス
        $response = $this->actingAs($user)->get('/myPage');

        // 購入した商品が表示されているか確認
        $response->assertSee('購入した商品');
        $response->assertSee($item->item_name); // アイテム名が表示されていることを確認
        $response->assertSee($item->item_img_pass); // アイテム画像がある場合は画像URLが表示されているか確認
    }
}
