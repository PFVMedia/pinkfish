<?php

use App\Filament\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPosts\Pages\EditBlogPost;
use App\Filament\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Models\BlogPost;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('lists blog posts', function () {
    $posts = BlogPost::factory()->count(3)->create();

    Livewire::test(ListBlogPosts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($posts);
});

it('can create a blog post', function () {
    Livewire::test(CreateBlogPost::class)
        ->fillForm([
            'title' => 'Test Blog Post',
            'slug' => 'test-blog-post',
            'body' => '<p>Test content</p>',
            'published_at' => '2026-04-06',
            'is_published' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas('blog_posts', ['title' => 'Test Blog Post']);
});

it('can update a blog post', function () {
    $post = BlogPost::factory()->create();

    Livewire::test(EditBlogPost::class, ['record' => $post->id])
        ->fillForm([
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'body' => '<p>Updated content</p>',
            'published_at' => '2026-04-06',
            'is_published' => false,
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => 'Updated Title']);
});

it('can delete a blog post', function () {
    $post = BlogPost::factory()->create();

    Livewire::test(EditBlogPost::class, ['record' => $post->id])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
});
