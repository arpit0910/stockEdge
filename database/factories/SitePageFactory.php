<?php

namespace Database\Factories;

use App\Models\SitePage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SitePage>
 */
class SitePageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(),
            'eyebrow' => strtoupper(fake()->words(2, true)),
            'summary' => fake()->paragraph(),
            'body' => fake()->paragraphs(3, true),
            'published' => true,
            'show_in_footer' => false,
            'system' => false,
            'position' => fake()->numberBetween(1, 100),
        ];
    }
}
