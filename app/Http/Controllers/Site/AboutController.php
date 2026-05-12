<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('about');

        return Inertia::render('Site/About', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'About'),
        ]);
    }
}
