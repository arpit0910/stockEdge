<?php

namespace Database\Factories;

use App\Models\AdminActivity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdminActivity>
 */
class AdminActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'actor' => fake()->name(),
            'action' => 'updated',
            'resource' => 'reports',
            'record_id' => fake()->numberBetween(1, 100),
            'label' => fake()->sentence(3),
            'changed_fields' => ['title'],
        ];
    }
}
