<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * モデルの名前を定義
     * 
     * @var string
     */
    protected $model = Item::class;

    /**
     * モデルのデフォルトの状態を定義
     *
     * @return array
     */
    public function definition()
    {
        return [
            'seller_id' => User::factory(), 
            'item_name' => $this->faker->word(), 
            'price' => $this->faker->numberBetween(1000, 100000), 
            'brand_name' => $this->faker->company(), 
            'item_img_pass' => 'storage/item_images/sample.jpg', 
            'description' => $this->faker->text(255),
            'condition_id' => Condition::factory(),
            'is_active' => $this->faker->boolean(80), 
            'purchaser_id' => null,
        ];
    }
}
