<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContentBlock;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('home');

        return Inertia::render('Site/Home', [
            'page' => $page,
            'content' => ContentBlock::allKeyed(),
            'latestPosts' => BlogPost::published()
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
