<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestForUserRegistration extends TestCase
{
    use RefreshDatabase;

    /**
     * 会員登録時のバリデーションをテスト。
     *
     * @return void
     */
    public function test_name_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // 必要なデータを入力せずに登録フォームを送信
        $response = $this->postJson('/register', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'name' => '', // 名前は空
        ]);

    $response->assertStatus(422);

    $this->assertEquals('お名前を入力してください', $response->json('errors.name.0'));

    }

    public function test_email_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // メールアドレスを入力せずに登録フォームを送信
        $response = $this->postJson('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'email' => '', // メールアドレスは空
        ]);

        $response->assertStatus(422);

        $this->assertEquals('メールアドレスを入力してください', $response->json('errors.email.0'));
    }

    public function test_password_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードを入力せずに登録フォームを送信
        $response = $this->postJson('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => '', // パスワードは空
            'password_confirmation' => '', // 確認用パスワードも空
        ]);

        $response->assertStatus(422);

        $this->assertEquals('パスワードを入力してください', $response->json('errors.password.0'));
    }

    public function test_password_too_short()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードが8文字未満の入力を送信
        $response = $this->postJson('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'short', // パスワードが短すぎる
            'password_confirmation' => 'short', // 確認用パスワードも同じ
        ]);

        $response->assertStatus(422);

        $this->assertEquals('パスワードは8文字以上で入力してください', $response->json('errors.password.0'));
    }

    public function test_password_confirmation_mismatch()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードと確認用パスワードが一致しない場合
        $response = $this->postJson('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword', // 確認用パスワードが異なる
        ]);

        $response->assertStatus(422);

        $this->assertEquals('パスワードと一致しません', $response->json('errors.password.0'));
    }

    public function test_registration_success()
    {
        // ユーザー登録のデータを準備
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // POSTリクエストでユーザー登録をシミュレート
        $response = $this->post(route('register'), $userData);

        // ユーザーがデータベースに保存されたことを確認
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);

        // ユーザーがメインページにリダイレクトされることを確認
        $response->assertRedirect('/'); 
    }
}
