<?php

namespace Database\Factories;

use App\Models\SiteSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSection>
 */
class SiteSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'layout' => 'text',
            'eyebrow' => strtoupper(fake()->words(2, true)),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'item_limit' => 4,
            'position' => fake()->numberBetween(1, 100),
            'published' => true,
        ];
    }
}
