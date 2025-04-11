<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestForProfile extends TestCase
{
    use RefreshDatabase;

    public function test_profile_form_prefills_existing_profile_data()
    {
        Storage::fake('public');

        // 1. ダミーユーザーとプロフィール画像を作成
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('avatar.jpg');
        $imagePath = $image->store('profile_images', 'public');

        // 2. プロフィール作成
        $profile = Profile::create([
            'user_id' => $user->id,
            'user_name' => 'テスト太郎',
            'postal_code' => '123-4567',
            'address' => '東京都テスト市',
            'building_name' => 'テストビル301',
            'user_image_pass' => basename($imagePath),
        ]);

        // 3. ログインしてプロフィール設定ページにアクセス
        $response = $this->actingAs($user)->get('/myPage/profile');

        // 4. 表示確認
        $response->assertStatus(200);
        $response->assertSee('プロフィール設定');
        $response->assertSee('テスト太郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都テスト市');
        $response->assertSee('テストビル301');
        $response->assertSee('storage/profile_images/' . basename($imagePath)); // 画像表示の確認
    }
}
