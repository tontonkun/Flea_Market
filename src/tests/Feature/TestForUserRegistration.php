<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestForUserRegistration extends TestCase
{
    /**
     * 会員登録時のバリデーションをテスト。
     *
     * @return void
     */
    public function test_name_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // 必要なデータを入力せずに登録フォームを送信
        $response = $this->post('/register', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'name' => '', // 名前は空
        ]);

        // バリデーションエラーが発生し、/register にリダイレクトされることを確認
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('name'); // name フィールドにエラーが含まれていることを確認
        $response->assertSee('お名前を入力してください');  // エラーメッセージが表示されているかを確認
    }

    public function test_email_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // メールアドレスを入力せずに登録フォームを送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'email' => '', // メールアドレスは空
        ]);

        // バリデーションエラーが発生し、/register にリダイレクトされることを確認
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email'); // email フィールドにエラーが含まれていることを確認
        $response->assertSee('メールアドレスを入力してください');  // エラーメッセージが表示されているかを確認
    }

    public function test_password_validation_required()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードを入力せずに登録フォームを送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => '', // パスワードは空
            'password_confirmation' => '', // 確認用パスワードも空
        ]);

        // バリデーションエラーが発生し、/register にリダイレクトされることを確認
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password'); // password フィールドにエラーが含まれていることを確認
        $response->assertSee('パスワードを入力してください');  // エラーメッセージが表示されているかを確認
    }

    public function test_password_too_short()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードが8文字未満の入力を送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'short', // パスワードが短すぎる
            'password_confirmation' => 'short', // 確認用パスワードも同じ
        ]);

        // バリデーションエラーが発生し、/register にリダイレクトされることを確認
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password'); // password フィールドにエラーが含まれていることを確認
        $response->assertSee('パスワードは8文字以上で入力してください');  // エラーメッセージが表示されているかを確認
    }

    public function test_password_confirmation_mismatch()
    {
        $this->withoutMiddleware(); // ミドルウェアを無効化して、直接リクエストが届くようにする

        // パスワードと確認用パスワードが一致しない場合
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword', // 確認用パスワードが異なる
        ]);

        // バリデーションエラーが発生し、/register にリダイレクトされることを確認
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password'); // password フィールドにエラーが含まれていることを確認
        $response->assertSee('パスワードと一致しません');  // エラーメッセージが表示されているかを確認
    }

    public function test_registration_success()
    {
        // 正常なデータを使って登録を試みる
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 成功した場合、/ にリダイレクトされることを確認
        $response->assertRedirect('/');
        $response->assertSessionHas('status', '会員登録が完了しました！');  // 成功メッセージが表示されることを確認
    }
}
