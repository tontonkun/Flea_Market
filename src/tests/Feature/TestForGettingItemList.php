<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestForGettingItemList extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーを実行してデータを挿入
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /** @test */
    public function it_displays_recommended_items()
    {
        // おすすめ商品を作成（Seederで挿入されたデータを使用）
        $recommendedItem = Item::where('is_active', true)->first();

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // レスポンスにおすすめ商品が含まれていることを確認
        $response->assertStatus(200);
        $response->assertSee($recommendedItem->item_name);
    }

    /** @test */
    public function it_displays_sold_label_for_sold_items()
    {
        // is_active = false の商品を1つ作成
        $soldItem = Item::factory()->create([
            'is_active' => false,
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get('/item/' . $soldItem->id);

        // レスポンスに「売却済み」ラベルが含まれていることを確認
        $response->assertStatus(200); // ステータスコードが200であることを確認
        $response->assertSee('Sold'); // 「売却済み」というラベルが表示されるか確認
    }


    /** @test */
    public function it_does_not_display_items_user_has_sold()
    {
        // ユーザー作成してログイン
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // ログインユーザーが出品した商品
        $myItem = \App\Models\Item::factory()->create([
            'seller_id' => $user->id,
            'is_active' => true,
            'item_name' => 'My Test Item'
        ]);

        // 他人が出品した商品
        $otherItem = \App\Models\Item::factory()->create([
            'is_active' => true,
            'item_name' => 'Other User Item'
        ]);

        // トップページにアクセス
        $response = $this->get('/');

        // 自分の出品した商品は表示されない
        $response->assertDontSee('My Test Item');

        // 他人の出品は表示される
        $response->assertSee('Other User Item');
    }
}
