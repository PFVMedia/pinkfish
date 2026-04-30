<?php

use App\Models\BlogPost;

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

it('shares background_style site setting with inertia', function () {
    \App\Models\SiteSetting::updateOrCreate(
        ['key' => 'background_style'],
        ['group' => 'general', 'value' => 'cubes'],
    );
    \Illuminate\Support\Facades\Cache::forget('site_settings');

    $this->get('/')
        ->assertInertia(fn ($page) => $page->where('siteSettings.background_style', 'cubes'));
});
