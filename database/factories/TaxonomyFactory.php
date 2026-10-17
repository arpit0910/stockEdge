<?php

namespace Database\Factories;

use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Taxonomy>
 */
class TaxonomyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kind' => 'category',
            'name' => fake()->unique()->words(2, true),
            'display_name' => fake()->words(3, true),
            'label' => strtoupper(fake()->words(2, true)),
            'description' => fake()->sentence(),
            'position' => fake()->numberBetween(1, 100),
            'published' => true,
        ];
    }
}
