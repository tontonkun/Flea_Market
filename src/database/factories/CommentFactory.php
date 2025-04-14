<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User; 
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
         return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'comment' => $this->faker->sentence(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
