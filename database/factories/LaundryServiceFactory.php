<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LaundryService>
 */
class LaundryServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'laundry_id' => null,
            'name' => fake()->sentence(2),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(4000, 10000),
            'icon' =>  fake()->image(public_path('img/icon/'), 100, 100, false),
        ];
    }
}
