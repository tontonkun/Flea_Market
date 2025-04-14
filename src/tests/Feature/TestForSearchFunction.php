<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestForSearchFunction extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_search_results()
    {
        // データ準備
        $matchItem = Item::factory()->create([
            'item_name' => '高級腕時計',
            'is_active' => true,
        ]);
        $nonMatchItem = Item::factory()->create([
            'item_name' => 'スポーツバッグ',
            'is_active' => true,
        ]);

        // クエリで検索
        $response = $this->get('/?query=腕時計');

        // 検索結果にマッチする商品だけが表示されること
        $response->assertStatus(200);
        $response->assertSee('高級腕時計');
        $response->assertDontSee('スポーツバッグ');
    }

    /** @test */
    public function search_box_retains_query_parameter()
    {
        // 検索クエリをつけてページにアクセス
        $response = $this->get('/?query=テスト商品');

        // 正常なステータスコード
        $response->assertStatus(200);

        // 検索フォーム内に値が保持されているか確認
        $response->assertSee('name="query" value="テスト商品"', false); // false でHTMLエスケープ無視
    }

    /** @test */
    public function search_box_is_empty_when_no_query_parameter()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // 空の状態を確認
        $response->assertSee('name="query" value=""', false);
    }
}
