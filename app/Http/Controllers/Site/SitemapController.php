<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];
        $seen = [];

        $add = function (string $loc, ?string $lastmod, string $changefreq, string $priority) use (&$urls, &$seen): void {
            $loc = rtrim($loc, '/') ?: url('/');
            if (isset($seen[$loc])) {
                return;
            }
            $seen[$loc] = true;
            $urls[] = compact('loc', 'lastmod', 'changefreq', 'priority');
        };

        $add(route('home'), null, 'weekly', '1.0');

        foreach ($this->menuPaths() as $path) {
            $add(url($path), null, 'monthly', '0.7');
        }

        $add(route('blog.index'), null, 'weekly', '0.8');
        $add(route('contact'), null, 'yearly', '0.5');
        $add(route('privacy'), null, 'yearly', '0.3');
        $add(route('disclaimer'), null, 'yearly', '0.3');

        BlogPost::published()
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at', 'published_at'])
            ->each(function (BlogPost $post) use ($add): void {
                $add(
                    route('blog.show', $post->slug),
                    ($post->updated_at ?? $post->published_at)?->toAtomString(),
                    'monthly',
                    '0.7',
                );
            });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Collect internal URL paths from the Filament-managed nav and footer settings.
     *
     * @return array<int, string>
     */
    private function menuPaths(): array
    {
        $paths = [];

        $mainNav = SiteSetting::get('main_nav', []);
        if (is_array($mainNav)) {
            foreach ($mainNav as $item) {
                if (is_array($item) && isset($item['href']) && $this->isInternalPath($item['href'])) {
                    $paths[] = $item['href'];
                }
            }
        }

        $footerColumns = SiteSetting::get('footer_columns', []);
        if (is_array($footerColumns)) {
            foreach ($footerColumns as $column) {
                if (! is_array($column) || ! isset($column['links']) || ! is_array($column['links'])) {
                    continue;
                }
                foreach ($column['links'] as $link) {
                    if (is_array($link) && isset($link['href']) && $this->isInternalPath($link['href'])) {
                        $paths[] = $link['href'];
                    }
                }
            }
        }

        return $paths;
    }

    private function isInternalPath(mixed $href): bool
    {
        if (! is_string($href) || $href === '') {
            return false;
        }

        return str_starts_with($href, '/') && ! str_starts_with($href, '//');
    }
}
