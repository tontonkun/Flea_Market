<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Condition; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TestForCommentFunction extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_submit_comment()
    {
        // 条件データがない場合に備えて作成
        Condition::factory()->create();  // 条件データを1つ作成

        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン処理
        $this->actingAs($user);

        $commentData = [
            'comment' => 'Test comment.',
        ];

        $response = $this->post(route('item.addComment', ['id' => $item->id]), $commentData);

        // コメントが正常に保存されたか
        $this->assertDatabaseHas('comments', $commentData);
    }

    /** @test */
    public function logged_in_user_can_submit_comment_and_increase_comment_count()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // コメント内容
        $commentData = [
            'comment' => 'This is a test comment.',
        ];

        // コメントを送信
        $response = $this->post(route('item.addComment', ['id' => $item->id]), $commentData);

        // コメントがデータベースに保存されたことを確認
        $this->assertDatabaseHas('comments', $commentData);

        // コメント数が1増えたことを確認
        $this->assertEquals(1, Comment::where('item_id', $item->id)->count());

        // 商品詳細ページにリダイレクトされることを確認
        $response->assertRedirect(route('item.showDetail', ['id' => $item->id]));
    }

    /** @test */
    public function guest_user_cannot_submit_comment()
    {
        // 商品を作成
        $item = Item::factory()->create();

        // ゲスト（ログインしていない）でコメント送信を試みる
        $response = $this->post(route('item.addComment', ['id' => $item->id]), [
            'comment' => 'This is a test comment.',
        ]);

        // ログインを求めるエラーメッセージが表示される
        $response->assertSessionHasErrors('error');
    }

    /** @test */
    public function comment_is_required()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // コメントを送信（空コメント）
        $response = $this->post(route('item.addComment', ['id' => $item->id]), [
            'comment' => '',
        ]);

        // バリデーションエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors('comment');
    }

    /** @test */
    public function comment_cannot_exceed_max_length()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // コメントが255文字を超える
        $longComment = str_repeat('A', 256);

        // コメントを送信（長すぎるコメント）
        $response = $this->post(route('item.addComment', ['id' => $item->id]), [
            'comment' => $longComment,
        ]);

        // バリデーションエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors('comment');
}
}
