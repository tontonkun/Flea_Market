<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TestForUserLogin extends TestCase
{
    /**
     * ログイン時のメールアドレスのバリデーションをテスト。
     *
     * @return void
     */
    public function test_email_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // メールアドレスを入力せずにログインフォームを送信
        $response = $this->post('/login', [
            'email' => '', // メールアドレスは空
            'password' => 'password123',
        ]);

        // バリデーションエラーが発生し、/login にリダイレクトされることを確認
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email'); // email フィールドにエラーが含まれていることを確認
        $response->assertSee('メールアドレスを入力してください');  // エラーメッセージが表示されているかを確認
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
        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => '', // パスワードは空
        ]);

        // バリデーションエラーが発生し、/login にリダイレクトされることを確認
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('password'); // password フィールドにエラーが含まれていることを確認
        $response->assertSee('パスワードを入力してください');  // エラーメッセージが表示されているかを確認
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
        $response = $this->post('/login', [
            'email' => 'nonexistentuser@example.com', // 存在しないユーザー
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');// エラーメッセージが表示され、/login にリダイレクトされることを確認
        $response->assertSessionHasErrors('email'); // email フィールドにエラーが含まれていることを確認
        $response->assertSee('ログイン情報が登録されていません'); // ユーザーが見つからないエラーメッセージが表示されることを確認
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

        // ログイン後にダッシュボード（またはトップページ）にリダイレクトされることを確認
        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'ログインに成功しました！');  // 成功メッセージが表示されることを確認
    }
}
