<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Page;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class LinksController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('links');

        return Inertia::render('Site/Links', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'Links'),
            'links' => Link::orderBy('sort_order')->get(),
        ]);
    }
}
