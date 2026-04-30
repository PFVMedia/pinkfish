<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'document_category_id' => DocumentCategory::factory(),
            'file_path' => 'documents/'.fake()->uuid().'.pdf',
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
