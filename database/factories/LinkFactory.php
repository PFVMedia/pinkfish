<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'url' => fake()->url(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['Development', 'Design', 'Marketing', 'General']),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
