<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\PurchasedItem;

class TestForMyPage extends TestCase
{
    use RefreshDatabase;

    public function test_my_page_displays_profile_and_items_correctly()
    {
        Storage::fake('public'); // 画像テストのため

        // ユーザー作成 & ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // プロフィール作成（画像も含む）
        $fakeImage = UploadedFile::fake()->image('avatar.jpg');
        $imagePath = $fakeImage->store('profile_images', 'public');

        $profile = Profile::create([
            'user_id' => $user->id,
            'user_name' => 'テストユーザー',
            'user_image_pass' => basename($imagePath),
            'postal_code' => '111-1111',
            'address' => 'テスト住所',
            'building_name' => 'テストビル',
        ]);

        // 出品した商品
        $postedItem = Item::factory()->create([
            'seller_id' => $user->id,
            'item_name' => '出品商品テスト',
            'item_img_pass' => 'images/test1.jpg',
        ]);

        // 購入した商品
        $purchasedItem = PurchasedItem::create([
            'purchaser_id' => $user->id,
            'item_id' => $postedItem->id, // 同じ商品でも OK
            'item_name' => $postedItem->item_name,
            'shipping_postal_code' => '222-2222',
            'shipping_address' => '購入者住所',
            'shipping_building_name' => '購入者ビル',
            'payment_method' => 'credit_card',
        ]);

        // マイページへアクセス
        $response = $this->get('/myPage');

        // 表示内容の検証
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('出品商品テスト');
        $response->assertSee('購入した商品'); // タイトル確認

        // プロフィール画像が正しく表示されているか（srcにパスが含まれる）
        $response->assertSee('profile_images/' . basename($imagePath));
    }
}
