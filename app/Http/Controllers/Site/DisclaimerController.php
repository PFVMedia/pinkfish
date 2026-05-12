<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class DisclaimerController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('disclaimer');

        return Inertia::render('Site/Disclaimer', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'Disclaimer'),
        ]);
    }
}
