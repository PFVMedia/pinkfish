<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Tool;
use App\Support\SeoMeta;
use Inertia\Inertia;
use Inertia\Response;

class ToolsController extends Controller
{
    public function __invoke(): Response
    {
        $page = Page::findBySlug('tools');

        return Inertia::render('Site/Tools', [
            'page' => $page,
            'seo' => SeoMeta::fromPage($page, defaultTitle: 'Tools'),
            'tools' => Tool::orderBy('sort_order')->get(),
        ]);
    }
}
