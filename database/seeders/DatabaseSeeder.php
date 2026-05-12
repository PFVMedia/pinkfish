<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\ContentBlock;
use App\Models\Link;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $this->seedContentBlocks();
        $this->seedBlogPosts();
        $this->seedLinks();
        $this->seedTools();

        $this->call([
            PageSeeder::class,
            SiteSettingsSeeder::class,
        ]);
    }

    private function seedContentBlocks(): void
    {
        $blocks = [
            ['key' => 'home_welcome_title', 'label' => 'Homepage Heading', 'type' => 'text', 'value' => 'Building the Web, One Pixel at a Time'],
            ['key' => 'home_welcome_body', 'label' => 'Homepage Welcome Text', 'type' => 'html', 'value' => '<p>Welcome — replace this placeholder text from the admin area with a short intro to your business.</p>'],
            ['key' => 'home_notice_title', 'label' => 'Homepage Notice Title', 'type' => 'text', 'value' => ''],
            ['key' => 'home_notice_body', 'label' => 'Homepage Notice Body', 'type' => 'html', 'value' => ''],
            ['key' => 'about_body', 'label' => 'About Page Content', 'type' => 'html', 'value' => '<p>Edit this placeholder from the admin area with your company story and values.</p>'],
            ['key' => 'services_body', 'label' => 'Services Page Content', 'type' => 'html', 'value' => '<p>Edit this placeholder from the admin area with a description of the services you offer.</p>'],
            ['key' => 'contact_email', 'label' => 'Contact Email', 'type' => 'text', 'value' => ''],
            ['key' => 'contact_phone', 'label' => 'Contact Phone', 'type' => 'text', 'value' => ''],
            ['key' => 'contact_address', 'label' => 'Office Address', 'type' => 'html', 'value' => ''],
            ['key' => 'privacy_body', 'label' => 'Privacy Policy', 'type' => 'html', 'value' => '<p>Edit this placeholder from the admin area to describe how you handle visitor data.</p>'],
            ['key' => 'disclaimer_body', 'label' => 'Disclaimer', 'type' => 'html', 'value' => '<p>The information provided on this website is for general informational purposes only.</p>'],
        ];

        foreach ($blocks as $block) {
            ContentBlock::create($block);
        }
    }

    private function seedBlogPosts(): void
    {
        BlogPost::create([
            'title' => 'Welcome to the new site',
            'slug' => 'welcome',
            'body' => '<p>Replace this sample post from the admin area. It exists so the blog index has something to render on a fresh install.</p>',
            'published_at' => '2026-04-01',
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'Why Laravel is our framework of choice',
            'slug' => 'why-laravel',
            'body' => '<p>Laravel provides an elegant syntax, robust tooling, and a thriving community. Combined with Vue.js and Inertia.js, it allows us to build full-stack applications that are both powerful and maintainable.</p>',
            'published_at' => '2026-04-03',
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'The importance of responsive design',
            'slug' => 'responsive-design',
            'body' => '<p>With mobile traffic continuing to grow, responsive design is essential. This post explores techniques and best practices for building websites that work on every device.</p>',
            'published_at' => '2026-04-05',
            'is_published' => true,
        ]);
    }

    private function seedLinks(): void
    {
        $links = [
            ['title' => 'Laravel', 'url' => 'https://laravel.com', 'description' => 'The PHP framework for web artisans', 'category' => 'Frameworks'],
            ['title' => 'Vue.js', 'url' => 'https://vuejs.org', 'description' => 'The progressive JavaScript framework', 'category' => 'Frameworks'],
            ['title' => 'Tailwind CSS', 'url' => 'https://tailwindcss.com', 'description' => 'A utility-first CSS framework', 'category' => 'Frameworks'],
            ['title' => 'Inertia.js', 'url' => 'https://inertiajs.com', 'description' => 'Build single-page apps without building an API', 'category' => 'Frameworks'],
            ['title' => 'GitHub', 'url' => 'https://github.com', 'description' => 'Where the world builds software', 'category' => 'Tools'],
            ['title' => 'DigitalOcean', 'url' => 'https://digitalocean.com', 'description' => 'Cloud infrastructure provider', 'category' => 'Hosting'],
        ];

        foreach ($links as $i => $link) {
            Link::create(array_merge($link, ['sort_order' => $i]));
        }
    }

    private function seedTools(): void
    {
        $tools = [
            ['name' => 'Laravel Forge', 'description' => 'Server management and deployment platform for Laravel applications. Provisions servers, manages SSL, and deploys your code effortlessly.', 'url' => 'https://forge.laravel.com', 'sort_order' => 0],
            ['name' => 'Laravel Vapor', 'description' => 'Serverless deployment platform for Laravel, powered by AWS. Scale without limits.', 'url' => 'https://vapor.laravel.com', 'sort_order' => 1],
            ['name' => 'Pest PHP', 'description' => 'An elegant PHP testing framework with a focus on simplicity. Write beautiful, expressive tests.', 'url' => 'https://pestphp.com', 'sort_order' => 2],
            ['name' => 'Vite', 'description' => 'Next generation frontend tooling. Lightning fast HMR and optimized builds for modern web projects.', 'url' => 'https://vite.dev', 'sort_order' => 3],
            ['name' => 'TablePlus', 'description' => 'Modern, native database management tool with an intuitive GUI for MySQL, PostgreSQL, SQLite, and more.', 'url' => 'https://tableplus.com', 'sort_order' => 4],
            ['name' => 'Ray', 'description' => 'Debug with Ray to fix problems faster. A beautiful, lightweight debugging tool by Spatie.', 'url' => 'https://myray.app', 'sort_order' => 5],
        ];

        foreach ($tools as $tool) {
            Tool::create($tool);
        }
    }
}
