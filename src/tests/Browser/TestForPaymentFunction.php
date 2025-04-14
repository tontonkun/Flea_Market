<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PaymentSelectionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_payment_method_selection_updates_display()
    {
        // テスト用ユーザーとプロフィール作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building_name' => 'テストビル101',
        ]);

        // 商品を作成
        $item = Item::factory()->create([
            'item_name' => 'テスト商品',
            'price' => 9800,
            'item_img_pass' => null,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                    ->visit(route('showPurchasePage', ['item' => $item->id]))
                    ->assertSee('支払方法')
                    ->select('payment_method', 'credit_card') // セレクトボックスで変更
                    ->pause(500) // JS反映待ち
                    ->assertSeeIn('#paymentMethodDisplay', 'カード決済') // DOM更新の確認
                    ->select('payment_method', 'convenience_store')
                    ->pause(500)
                    ->assertSeeIn('#paymentMethodDisplay', 'コンビニ払い');
        });
    }
}
