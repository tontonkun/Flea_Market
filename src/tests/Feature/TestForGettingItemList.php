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
        // 売却済みの商品を作成（Seederで挿入されたデータを使用）
        $soldItem = Item::where('is_active', false)->first();

        // 商品詳細ページにアクセス
        $response = $this->get('/items/' . $soldItem->id);

        // レスポンスに「売却済み」ラベルが含まれていることを確認
        $response->assertStatus(200); // ステータスコードが200であることを確認
        $response->assertSee('売却済み'); // 「売却済み」というラベルが表示されるか確認
    }


    /** @test */
    // public function it_does_not_display_items_user_has_sold()
    // {
    //     // 売却済み商品（is_activeがfalse）のデータをシーダーで挿入されたデータから取得
    //     $soldItem = Item::where('is_active', false)->first();

    //     // 商品一覧ページにアクセス
    //     $response = $this->get('/');

    //     // 売却済み商品の名前が含まれていないことを確認
    //     $response->assertDontSee($soldItem->item_name);

    //     // "Sold" ラベルが含まれていないことを確認
    //     $response->assertSee('Sold');
    // }
}
