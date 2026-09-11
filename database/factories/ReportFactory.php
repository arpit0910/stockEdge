<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Report> */
class ReportFactory extends Factory
{
    public function definition(): array
    {
        return ['stock_id' => Stock::factory(), 'title' => fake()->sentence(), 'slug' => fake()->unique()->slug(), 'category' => 'Daily Analysis', 'rating' => 'Hold', 'summary' => 'Example research summary.', 'body' => "Private investment overview\nConfidential report body for access checks.", 'premium' => true, 'published' => true];
    }
}
