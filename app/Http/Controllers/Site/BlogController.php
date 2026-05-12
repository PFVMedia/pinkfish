<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Page;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        $page = Page::findBySlug('blog-index');

        return Inertia::render('Site/Blog/Index', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'Blog'),
            'posts' => BlogPost::published()
                ->orderByDesc('published_at')
                ->paginate(10),
        ]);
    }

    public function show(BlogPost $blogPost): Response
    {
        abort_unless($blogPost->is_published, 404);

        return Inertia::render('Site/Blog/Show', [
            'post' => $blogPost,
            'seo' => SeoMeta::fromBlogPost($blogPost),
        ]);
    }
}
