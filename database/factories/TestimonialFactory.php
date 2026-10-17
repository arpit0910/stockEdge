<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->jobTitle(),
            'quote' => fake()->paragraph(),
            'rating' => 5,
            'avatar_initials' => strtoupper(fake()->lexify('??')),
            'position' => fake()->numberBetween(1, 100),
            'published' => true,
        ];
    }
}
