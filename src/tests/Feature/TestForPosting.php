<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestForPosting extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面で必要な情報が保存できることを確認するテスト
     *
     * @return void
     */
    public function test_posting_page_saves_item_correctly()
    {
        $this->seed(\Database\Seeders\ConditionSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class); 

        // まず、ユーザーを作成
        $user = User::factory()->create();

        //ログイン
        $this->actingAs($user);

        // シーディングされた condition（1〜4）からランダムに取得
        $condition = \App\Models\Condition::inRandomOrder()->first();

        // 出品するデータ
        $itemData = [
            'item_name' => 'テスト商品',
            'price' => 5000,
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品です。',
            'condition_id' => $condition->id, // シードされた中から選ぶ
            'seller_id' => $user->id,  // 作成したユーザーIDを seller_id に設定
            'is_active' => 1,
            'selected_category' => ['ファッション', '家電'] // 複数のカテゴリーを選択
        ];

        // POSTリクエストを送信してデータを保存
        $response = $this->post('/postItems', $itemData);

        // 保存されたかどうかを確認
        $this->assertDatabaseHas('items', [
            'item_name' => 'テスト商品',
            'price' => 5000,
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品です。',
            'condition_id' => $condition->id,
            'seller_id' => $user->id,
            'is_active' => 1
        ]);

        // 商品IDを取得
        $item = Item::where('item_name', 'テスト商品')->first();

        // カテゴリーデータも保存されているかを確認（中間テーブルのチェック）
        $categoryIds = ['ファッション', '家電']; // 選択したカテゴリー名
        foreach ($categoryIds as $categoryName) {
            $category = Category::where('category', $categoryName)->first();
            
            // 中間テーブルに保存されているか確認
            $this->assertDatabaseHas('item_category', [
                'item_id' => $item->id, // 上記で取得した item_id
                'category_id' => $category->id // 取得した category_id
            ]);
        }
    }
}
