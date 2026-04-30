<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class LinksController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Site/Links', [
            'page' => Page::findBySlug('links'),
            'links' => Link::orderBy('sort_order')->get(),
        ]);
    }
}
