<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Profile;

class TestForFavoriteFunction extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_add_and_remove_favorite()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // アイテムを作成
        $item = Item::factory()->create();

        // 初めて「いいね」ボタンを押す
        $response = $this->post(route('item.addFavorite', ['id' => $item->id]));

        // いいねが追加されたことを確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // アイテム詳細ページにリダイレクトされることを確認
        $response->assertRedirect(route('item.showDetail', ['id' => $item->id]));

        // 再度「いいね」ボタンを押して解除する
        $response = $this->post(route('item.addFavorite', ['id' => $item->id]));

        // いいねが解除されたことを確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function test_user_sees_correct_favorite_count_when_loading_item_page()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // アイテムを作成
        $item = Item::factory()->create();

        // 最初はお気に入りがない状態
        $response = $this->get(route('item.showDetail', ['id' => $item->id]));
        $response->assertSee('0'); // いいね合計が0表示されるはず

        // アイテムに「いいね」する
        $this->post(route('item.addFavorite', ['id' => $item->id]));

        // いいねを追加した後、合計が1に増えていることを確認
        $response = $this->get(route('item.showDetail', ['id' => $item->id]));
        $response->assertSee('1'); // いいね合計が1表示されるはず
    }

    /** @test */
    public function test_favorite_icon_changes_when_item_is_favorited()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // アイテムを作成
        $item = Item::factory()->create();

        // アイテム詳細ページにアクセス
        $response = $this->get(route('item.showDetail', ['id' => $item->id]));

        // 初めていいねを押す前の状態
        $response->assertDontSee('active'); // アイコンにactiveクラスがないことを確認

        // 「いいね」ボタンを押す
        $this->post(route('item.addFavorite', ['id' => $item->id]));

        // アイコンがアクティブに変わっていることを確認
        $response = $this->get(route('item.showDetail', ['id' => $item->id]));
        $response->assertSee('active'); // アイコンにactiveクラスがあることを確認
    }
}
