<?php

namespace App\Models;

use Database\Factories\ContentBlockFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'label', 'type', 'value'])]
class ContentBlock extends Model
{
    /** @use HasFactory<ContentBlockFactory> */
    use HasFactory;

    /**
     * Get a content block value by key.
     */
    public static function get(string $key): string
    {
        return static::where('key', $key)->value('value') ?? '';
    }

    /**
     * Get all content blocks as a key => value array.
     *
     * @return array<string, string>
     */
    public static function allKeyed(): array
    {
        return static::pluck('value', 'key')->toArray();
    }
}
