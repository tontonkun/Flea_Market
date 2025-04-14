<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'user_image_pass' => null, 
            'user_name' => $this->faker->name,
            'postal_code' => $this->faker->postcode,
            'address' => $this->faker->address,
            'building_name' => $this->faker->optional()->secondaryAddress,
        ];
    }
}
