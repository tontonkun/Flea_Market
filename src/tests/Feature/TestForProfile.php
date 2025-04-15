<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestForProfile extends TestCase
{
    use RefreshDatabase;

    public function test_profile_form_prefills_existing_profile_data()
    {
        // ユーザーとプロフィールを作成
        $user = User::factory()->create();

        // 仮のプロフィール画像ファイル名
        $imageName = 'sample.jpg';

        // プロフィール作成（画像ファイル名のみ）
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'user_name' => 'テスト太郎',
            'postal_code' => '123-4567',
            'address' => '東京都港区',
            'building_name' => 'テストビル101',
            'user_image_pass' => $imageName, // 実際の画像は使用しない
        ]);

        // 認証状態にする
        $response = $this->actingAs($user)->get('myPage/profile'); // showProfile へアクセス

        $response->assertStatus(200);

        // HTML に各値が含まれていることを確認
        $response->assertSee('value="テスト太郎"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="東京都港区"', false);
        $response->assertSee('value="テストビル101"', false);

        // 画像のファイル名が表示されるか
        $response->assertSee($imageName, false);
    }
}
