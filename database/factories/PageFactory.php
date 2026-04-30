<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'blocks' => [],
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    public function home(): static
    {
        return $this->state(fn () => [
            'title' => 'Home',
            'slug' => 'home',
            'blocks' => [
                ['type' => 'hero', 'data' => ['heading' => 'Build something extraordinary', 'subtitle' => 'Test subtitle', 'buttons' => []]],
            ],
        ]);
    }

    public function withSlug(string $slug): static
    {
        return $this->state(fn () => ['slug' => $slug]);
    }
}
