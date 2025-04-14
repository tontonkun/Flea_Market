<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Profile;

class TestForItemDetail extends TestCase
{
    use RefreshDatabase;

    /** @test */
   public function test_item_detail_page_displays_all_required_information()
    {
        // ユーザー作成とプロフィール登録
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'user_name' => 'テストユーザー',
            'user_image_pass' => null,
        ]);

        // 条件とカテゴリ
        $condition = Condition::factory()->create(['condition' => '新品']);
        $category = Category::factory()->create(['category' => 'アパレル']);

        // 商品作成
        $item = Item::factory()->create([
            'item_name' => 'テスト商品',
            'brand_name' => 'ブランド名',
            'price' => 12345,
            'item_img_pass' => 'images/test_item.jpg',
            'is_active' => true,
            'condition_id' => $condition->id,
        ]);

        $item->category()->attach($category->id);

        // いいねとコメント
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです。',
        ]);

        // ページアクセス
        $response = $this->actingAs($user)->get(route('item.showDetail', ['id' => $item->id]));

        // 内容確認
        $response->assertStatus(200);
        $response->assertSee('テスト商品');                     // 商品名
        $response->assertSee('ブランド名');                     // ブランド
        $response->assertSee('¥');                              // 円マーク
        $response->assertSee('12,345');                         // 価格（カンマ付き）
        $response->assertSee('新品');                           // 商品の状態
        $response->assertSee('アパレル');                       // カテゴリ
        $response->assertSee('テストユーザー');                 // ユーザー名
        $response->assertSee('これはテストコメントです。');      // コメント内容
        $response->assertSee((string) 1);                        // コメント数、いいね数など
    }

    public function test_item_detail_displays_multiple_categories()
    {
        // ユーザー & プロフィール作成
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        // カテゴリ複数作成
        $category1 = Category::factory()->create(['category' => 'トップス']);
        $category2 = Category::factory()->create(['category' => 'ボトムス']);
        $category3 = Category::factory()->create(['category' => 'シューズ']);

        // 商品作成 & カテゴリ紐付け
        $item = Item::factory()->create([
            'item_name' => '複数カテゴリ商品',
            'is_active' => true,
        ]);
        $item->category()->attach([$category1->id, $category2->id, $category3->id]);

        // テストアクセス
        $response = $this->actingAs($user)->get(route('item.showDetail', ['id' => $item->id]));

        // カテゴリ名が全て表示されているか確認
        $response->assertStatus(200);
        $response->assertSee('トップス');
        $response->assertSee('ボトムス');
        $response->assertSee('シューズ');
    }
}
