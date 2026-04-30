<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Tool;
use Inertia\Inertia;
use Inertia\Response;

class ToolsController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Site/Tools', [
            'page' => Page::findBySlug('tools'),
            'tools' => Tool::orderBy('sort_order')->get(),
        ]);
    }
}
