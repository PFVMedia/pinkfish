<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class PrivacyController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('privacy');

        return Inertia::render('Site/Privacy', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'Privacy Policy'),
        ]);
    }
}
