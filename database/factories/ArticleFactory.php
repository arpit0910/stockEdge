<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Article> */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return ['title' => fake()->sentence(), 'slug' => fake()->unique()->slug(), 'topic' => 'Market News', 'image' => 'city', 'summary' => 'Illustrative editorial summary.', 'body' => "Market perspective\nAn educational example.", 'published' => true];
    }
}
