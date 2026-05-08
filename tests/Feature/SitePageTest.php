<?php

use App\Models\BlogPost;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

it('loads the homepage', function () {
    $this->get('/')->assertSuccessful();
});

it('loads the about page', function () {
    $this->get('/about')->assertSuccessful();
});

it('loads the services page', function () {
    $this->get('/services')->assertSuccessful();
});

it('loads the tools page', function () {
    $this->get('/tools')->assertSuccessful();
});

it('loads the links page', function () {
    $this->get('/links')->assertSuccessful();
});

it('loads the blog index page', function () {
    $this->get('/blog')->assertSuccessful();
});

it('loads a blog post', function () {
    $post = BlogPost::factory()->create(['is_published' => true]);

    $this->get("/blog/{$post->slug}")->assertSuccessful();
});

it('returns 404 for unpublished blog post', function () {
    $post = BlogPost::factory()->create(['is_published' => false]);

    $this->get("/blog/{$post->slug}")->assertNotFound();
});

it('loads the contact page', function () {
    $this->get('/contact')->assertSuccessful();
});

it('loads the privacy page', function () {
    $this->get('/privacy')->assertSuccessful();
});

it('loads the disclaimer page', function () {
    $this->get('/disclaimer')->assertSuccessful();
});

it('serves robots.txt with a sitemap reference', function () {
    $response = $this->get('/robots.txt');

    $response->assertSuccessful()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('User-agent: *')
        ->assertSee('Sitemap: '.route('sitemap'));
});

it('serves the sitemap with static routes and published blog posts', function () {
    $published = BlogPost::factory()->create(['is_published' => true, 'slug' => 'sitemap-published']);
    BlogPost::factory()->create(['is_published' => false, 'slug' => 'sitemap-draft']);

    $response = $this->get('/sitemap.xml');

    $response->assertSuccessful()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee('<urlset', false)
        ->assertSee(route('home'), false)
        ->assertSee(route('blog.show', $published->slug), false)
        ->assertDontSee(route('blog.show', 'sitemap-draft'), false);
});

it('includes URLs from Filament main_nav and footer_columns in the sitemap', function () {
    SiteSetting::updateOrCreate(
        ['key' => 'main_nav'],
        ['group' => 'navigation', 'value' => [
            ['name' => 'Pricing', 'href' => '/pricing'],
            ['name' => 'External', 'href' => 'https://example.com/ignored'],
        ]],
    );
    SiteSetting::updateOrCreate(
        ['key' => 'footer_columns'],
        ['group' => 'footer', 'value' => [
            ['title' => 'Resources', 'links' => [
                ['name' => 'Case studies', 'href' => '/case-studies'],
                ['name' => 'Email us', 'href' => 'mailto:hi@example.com'],
            ]],
        ]],
    );
    Cache::forget('site_settings');

    $response = $this->get('/sitemap.xml');

    $response->assertSuccessful()
        ->assertSee(url('/pricing'), false)
        ->assertSee(url('/case-studies'), false)
        ->assertDontSee('example.com/ignored', false)
        ->assertDontSee('mailto:', false);
});

it('shares background_style site setting with inertia', function () {
    SiteSetting::updateOrCreate(
        ['key' => 'background_style'],
        ['group' => 'general', 'value' => 'cubes'],
    );
    Cache::forget('site_settings');

    $this->get('/')
        ->assertInertia(fn ($page) => $page->where('siteSettings.background_style', 'cubes'));
});
