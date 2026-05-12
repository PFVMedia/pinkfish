<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Page;
use Illuminate\Support\Str;

class SeoMeta
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function fromPage(?Page $page, string $defaultTitle, ?string $defaultDescription = null, array $overrides = []): array
    {
        $title = $page?->meta_title ?: ($page?->title ?: $defaultTitle);
        $description = $page?->meta_description ?: $defaultDescription;

        return array_merge([
            'title' => $title,
            'description' => $description,
            'canonical' => url(request()->path() === '/' ? '/' : '/'.ltrim(request()->path(), '/')),
            'og_image' => null,
            'type' => 'website',
        ], $overrides);
    }

    /**
     * @return array<string, mixed>
     */
    public static function fromBlogPost(BlogPost $post): array
    {
        return [
            'title' => $post->title,
            'description' => $post->meta_description ?: self::excerpt($post->body),
            'canonical' => url('/blog/'.$post->slug),
            'og_image' => $post->og_image ? url('storage/'.$post->og_image) : null,
            'type' => 'article',
            'published_time' => $post->published_at?->toAtomString(),
            'modified_time' => $post->updated_at?->toAtomString(),
        ];
    }

    private static function excerpt(?string $html, int $length = 160): ?string
    {
        if (! $html) {
            return null;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));

        return $text === '' ? null : Str::limit($text, $length);
    }
}
