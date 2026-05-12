<?php

use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\DisclaimerController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LinksController;
use App\Http\Controllers\Site\PrivacyController;
use App\Http\Controllers\Site\ServicesController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\Site\ToolsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/services', ServicesController::class)->name('services');
Route::get('/tools', ToolsController::class)->name('tools');
Route::get('/links', LinksController::class)->name('links');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send')->middleware('throttle:3,1');
Route::get('/privacy', PrivacyController::class)->name('privacy');
Route::get('/disclaimer', DisclaimerController::class)->name('disclaimer');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin',
        'Disallow: /login',
        'Disallow: /register',
        'Disallow: /forgot-password',
        'Disallow: /reset-password',
        'Disallow: /email/verify',
        'Disallow: /user/',
        '',
        'Sitemap: '.route('sitemap'),
        '',
    ];

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
})->name('robots');
