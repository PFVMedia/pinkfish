<?php

use App\Models\BlogPost;
use App\Models\Page;
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

it('stores meta_description and og_image on a blog post', function () {
    $post = BlogPost::factory()->create([
        'meta_description' => 'A concise summary for search engines.',
        'og_image' => 'page-images/social-card.png',
    ]);

    expect($post->fresh())
        ->meta_description->toBe('A concise summary for search engines.')
        ->og_image->toBe('page-images/social-card.png');
});

it('returns 404 for unpublished blog post', function () {
    $post = BlogPost::factory()->create(['is_published' => false]);

    $this->get("/blog/{$post->slug}")->assertNotFound();
});

it('loads the contact page', function () {
    $this->get('/contact')->assertSuccessful();
});

it('does not load the turnstile script on non-contact pages', function () {
    config()->set('services.turnstile.key', 'test-site-key');

    $this->get('/')
        ->assertSuccessful()
        ->assertDontSee('challenges.cloudflare.com/turnstile', false);
});

it('loads the turnstile script on the contact page', function () {
    config()->set('services.turnstile.key', 'test-site-key');

    $this->get('/contact')
        ->assertSuccessful()
        ->assertSee('challenges.cloudflare.com/turnstile', false);
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

it('disallows admin and auth paths in robots.txt', function () {
    $response = $this->get('/robots.txt');

    $response->assertSuccessful()
        ->assertSee('Disallow: /admin')
        ->assertSee('Disallow: /login')
        ->assertSee('Disallow: /register')
        ->assertSee('Disallow: /forgot-password')
        ->assertSee('Disallow: /reset-password');
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

it('exposes seo meta on the blog post page', function () {
    $post = BlogPost::factory()->create([
        'slug' => 'seo-blog-post',
        'is_published' => true,
        'meta_description' => 'A focused summary used in search snippets.',
        'og_image' => 'blog-og/sample.png',
    ]);

    $this->get("/blog/{$post->slug}")
        ->assertInertia(fn ($page) => $page
            ->where('seo.title', $post->title)
            ->where('seo.description', 'A focused summary used in search snippets.')
            ->where('seo.canonical', url("/blog/{$post->slug}"))
            ->where('seo.og_image', url('storage/blog-og/sample.png'))
            ->where('seo.type', 'article')
        );
});

it('falls back to defaults for blog posts without meta_description', function () {
    $post = BlogPost::factory()->create([
        'is_published' => true,
        'meta_description' => null,
        'og_image' => null,
        'body' => '<p>This is the body. It has multiple sentences. More content here.</p>',
    ]);

    $this->get("/blog/{$post->slug}")
        ->assertInertia(fn ($page) => $page
            ->whereNot('seo.description', null)
            ->where('seo.og_image', null)
        );
});

it('exposes seo meta from a Page record', function () {
    Page::factory()->create([
        'slug' => 'about',
        'is_published' => true,
        'meta_title' => 'About Pink Fish',
        'meta_description' => 'Who we are and what we do.',
    ]);

    $this->get('/about')
        ->assertInertia(fn ($page) => $page
            ->where('seo.title', 'About Pink Fish')
            ->where('seo.description', 'Who we are and what we do.')
            ->where('seo.canonical', url('/about'))
        );
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
