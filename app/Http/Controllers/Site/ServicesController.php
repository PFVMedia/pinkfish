<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Site/Services', [
            'page' => Page::findBySlug('services'),
        ]);
    }
}
