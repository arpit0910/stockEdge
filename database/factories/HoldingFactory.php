<?php

namespace Database\Factories;

use App\Models\Holding;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Holding>
 */
class HoldingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'stock_id' => Stock::factory(),
            'quantity' => fake()->randomFloat(4, 1, 1000),
            'buy_price' => fake()->randomFloat(4, 1, 1000),
        ];
    }
}
