<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('services');

        return Inertia::render('Site/Services', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'Services'),
        ]);
    }
}
