<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TestForUserLogout extends TestCase
{
    /**
     * ログアウト処理が正常に動作するかをテスト。
     *
     * @return void
     */
    public function test_logout()
    {
        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'), // パスワードを暗号化して設定
        ]);

        // ログインする
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログアウト後にホームページまたはログインページにリダイレクトされることを確認
        $response->assertRedirect('/login'); // ログアウト後、ログインページにリダイレクトされることを確認
        $this->assertGuest(); // ログアウト後、ゲスト状態であることを確認
    }
}
