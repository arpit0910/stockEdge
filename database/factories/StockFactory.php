<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Stock> */
class StockFactory extends Factory
{
    public function definition(): array
    {
        return ['symbol' => strtoupper(fake()->unique()->lexify('????')), 'name' => fake()->company(), 'sector' => 'Mining', 'cap' => 'Blue Chip', 'price' => 42.85, 'change' => 1.24, 'yield' => 5.2, 'description' => 'Illustrative company profile.'];
    }
}
