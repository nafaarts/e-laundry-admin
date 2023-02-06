<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laundry>
 */
class LaundryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'name' => fake()->sentence(3),
            'no_izin' => 'no-' . fake()->numberBetween(1234123, 12309823),
            'address' => fake()->address(),
            'district' => fake()->state(),
            'city' => fake()->city(),
            'lat' => null,
            'long' => null,
            'image' =>  fake()->image(public_path('img/laundry/'), 700, 400, false),
        ];
    }
}
