<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Stock> */
class StockFactory extends Factory
{
    public function definition(): array
    {
        return ['symbol' => strtoupper(fake()->unique()->lexify('????')), 'name' => fake()->company(), 'sector' => 'Mining', 'cap' => 'Blue Chip', 'price' => 42.85, 'day_high' => 43.50, 'day_low' => 41.90, 'change' => 1.24, 'yield' => 5.2, 'buy_price' => 38.40, 'target_price' => 46.50, 'recommendation_status' => 'Active', 'show_on_homepage' => true, 'homepage_position' => 10, 'description' => 'Illustrative company profile.'];
    }
}
