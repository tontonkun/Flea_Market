<?php

namespace Database\Factories;

use App\Models\PurchasedItem;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchasedItemFactory extends Factory
{
    protected $model = PurchasedItem::class;

    public function definition(): array
    {
        $item = Item::factory()->create();

        return [
            'purchaser_id' => User::factory(),
            'item_id' => $item->id,
            'item_name' => $item->item_name,
            'shipping_postal_code' => $this->faker->postcode,
            'shipping_address' => $this->faker->address,
            'shipping_building_name' => $this->faker->secondaryAddress,
            'payment_method' => $this->faker->randomElement(['クレジットカード', 'コンビニ払い', '代引き']),
        ];
    }
}
