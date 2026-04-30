<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'body' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'is_published' => true,
        ];
    }
}
