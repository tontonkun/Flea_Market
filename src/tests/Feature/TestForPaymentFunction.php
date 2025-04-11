<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaymentMethodDisplayTest extends DuskTestCase
{
    public function test_payment_method_selection_reflects_in_display()
    //このテストのみ実行コマンドは'php artisan dusk'
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                    ->visit('/purchase/' . $item->id) 
                    ->select('payment_method', 'credit_card')
                    ->pause(300) // JSが実行されるまで少し待つ
                    ->assertSeeIn('#paymentMethodDisplay', 'カード決済');
        });
    }
}
