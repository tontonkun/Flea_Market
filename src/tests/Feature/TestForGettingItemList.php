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
    /** @test */
    public function it_displays_sold_label_for_sold_items()
    {
        // 条件を先に作成
        $condition = \App\Models\Condition::find(1);

        // 有効な condition_id を指定して売却済みアイテムを作成
        $soldItem = Item::factory()->create([
            'is_active' => false,
            'condition_id' => $condition->id, // condition_id を適切に設定
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // レスポンスに「Sold」のラベルが含まれていることを確認
        $response->assertStatus(200);

        // 商品名が表示されるか確認
        $response->assertSee($soldItem->item_name);

        // Sold ラベルが含まれているか確認
        $response->assertSee('<div class="sold-label">Sold</div>');
    }


    /** @test */
    public function it_does_not_display_items_user_has_sold()
    {
        // 売却済み商品（is_activeがfalse）のデータをシーダーで挿入されたデータから取得
        $soldItem = Item::where('is_active', false)->first();

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // 売却済み商品の名前が含まれていないことを確認
        $response->assertDontSee($soldItem->item_name);

        // "Sold" ラベルが含まれていないことを確認
        $response->assertSee('Sold');
    }
}
