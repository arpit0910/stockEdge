<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'headline' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'features' => implode("\n", fake()->sentences(3)),
            'monthly_price' => 29,
            'yearly_price' => 290,
            'is_trial' => false,
            'featured' => false,
            'published' => true,
            'position' => fake()->numberBetween(1, 100),
        ];
    }
}
