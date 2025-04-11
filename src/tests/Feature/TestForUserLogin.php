<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TestForUserLogin extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン時のメールアドレスのバリデーションをテスト。
     *
     * @return void
     */
    public function test_email_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // メールアドレスを入力せずにログインフォームを送信
        $response = $this->postJson('/login', [
            'email' => '', // メールアドレスは空
            'password' => 'password123',
        ]);

        $response->assertStatus(422);

    $this->assertEquals('メールアドレスを入力してください', $response->json('errors.email.0'));
    }

    /**
     * ログイン時のパスワードのバリデーションをテスト。
     *
     * @return void
     */
    public function test_password_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードを入力せずにログインフォームを送信
        $response = $this->postJson('/login', [
            'email' => 'testuser@example.com',
            'password' => '', // パスワードは空
        ]);

        $response->assertStatus(422);

    $this->assertEquals('パスワードを入力してください', $response->json('errors.password.0'));
    }

    /**
     * 存在しないユーザーでログインした場合のテスト。
     *
     * @return void
     */
    public function test_login_user_not_found()
    {
    $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

    // 存在しないユーザーの情報でログインを試みる
    $response = $this->postJson('/login', [
        'email' => 'nonexistentuser@example.com', // 存在しないユーザー
        'password' => 'password123',
    ]);

    $response->assertStatus(422);

    // 存在しないユーザーに対して適切なエラーメッセージを確認
    $this->assertEquals('ログイン情報が登録されていません', $response->json('errors.email.0'));
    }

    /**
     * 正しいログイン情報でのログイン処理をテスト。
     *
     * @return void
     */
    public function test_successful_login()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'), // パスワードを暗号化して設定
        ]);

        // 正しいログイン情報でログインを試みる
        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // 302リダイレクトが返るはずなので、リダイレクト先を検証
        $response->assertRedirect('/');

        // ユーザーが認証されていることを確認
        $this->assertAuthenticatedAs($user);
    }
}