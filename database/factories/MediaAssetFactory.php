<?php

namespace Database\Factories;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaAsset>
 */
class MediaAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'path' => 'media/'.fake()->unique()->uuid().'.jpg',
            'alt_text' => fake()->sentence(),
            'size' => fake()->numberBetween(10_000, 2_000_000),
            'mime' => 'image/jpeg',
        ];
    }
}
