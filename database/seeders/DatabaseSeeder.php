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
            'email' => 'admin@pinkfishventures.com',
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
            ['key' => 'home_welcome_body', 'label' => 'Homepage Welcome Text', 'type' => 'html', 'value' => '<p>Pink Fish Ventures Inc. is a web development company specializing in modern, scalable web applications. We partner with businesses of all sizes to bring their digital vision to life.</p>'],
            ['key' => 'home_notice_title', 'label' => 'Homepage Notice Title', 'type' => 'text', 'value' => 'Now Accepting New Projects'],
            ['key' => 'home_notice_body', 'label' => 'Homepage Notice Body', 'type' => 'html', 'value' => '<p>We are currently accepting new clients for Q2 2026. Get in touch to discuss your project requirements.</p>'],
            ['key' => 'about_body', 'label' => 'About Page Content', 'type' => 'html', 'value' => '<p>Pink Fish Ventures Inc. was founded with a simple mission: build exceptional web experiences that drive results. Our team of experienced developers, designers, and strategists work together to deliver custom solutions tailored to each client\'s unique needs.</p><p>We specialize in Laravel, Vue.js, and modern full-stack development. From concept to deployment, we handle every aspect of your web project with care and expertise.</p>'],
            ['key' => 'services_body', 'label' => 'Services Page Content', 'type' => 'html', 'value' => '<p>We offer a comprehensive range of web development services designed to help your business succeed online.</p>'],
            ['key' => 'contact_email', 'label' => 'Contact Email', 'type' => 'text', 'value' => 'hello@pinkfishventures.com'],
            ['key' => 'contact_phone', 'label' => 'Contact Phone', 'type' => 'text', 'value' => '+1 (555) 123-4567'],
            ['key' => 'contact_address', 'label' => 'Office Address', 'type' => 'html', 'value' => '<p>123 Digital Lane<br>Suite 456<br>Vancouver, BC V6B 1A1<br>Canada</p>'],
            ['key' => 'privacy_body', 'label' => 'Privacy Policy', 'type' => 'html', 'value' => '<p>Pink Fish Ventures Inc. is committed to protecting the privacy of our clients and website visitors. This privacy policy outlines how we collect, use, and safeguard your personal information.</p><p>We collect information you voluntarily provide through our contact form, including your name, email address, and message content. This information is used solely to respond to your inquiry.</p><p>We do not sell, trade, or otherwise transfer your personal information to third parties. We implement appropriate security measures to protect against unauthorized access to your data.</p>'],
            ['key' => 'disclaimer_body', 'label' => 'Disclaimer', 'type' => 'html', 'value' => '<p>The information provided on this website is for general informational purposes only. While we strive to keep the information up to date and correct, we make no representations or warranties of any kind about the completeness, accuracy, reliability, or availability of the website or the information contained on it.</p>'],
        ];

        foreach ($blocks as $block) {
            ContentBlock::create($block);
        }
    }

    private function seedBlogPosts(): void
    {
        BlogPost::create([
            'title' => 'Welcome to Pink Fish Ventures',
            'slug' => 'welcome-to-pink-fish-ventures',
            'body' => '<p>We\'re excited to launch our new website! Pink Fish Ventures Inc. is dedicated to building modern, high-performance web applications using the latest technologies.</p><p>Stay tuned for updates on our projects, industry insights, and web development tips.</p>',
            'published_at' => '2026-04-01',
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'Why Laravel is Our Framework of Choice',
            'slug' => 'why-laravel-is-our-framework-of-choice',
            'body' => '<p>At Pink Fish Ventures, we\'ve built our practice around the Laravel ecosystem. Here\'s why we believe it\'s the best choice for modern web development.</p><p>Laravel provides an elegant syntax, robust tooling, and a thriving community. Combined with Vue.js and Inertia.js, it allows us to build full-stack applications that are both powerful and maintainable.</p>',
            'published_at' => '2026-04-03',
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'The Importance of Responsive Design in 2026',
            'slug' => 'the-importance-of-responsive-design-in-2026',
            'body' => '<p>With mobile traffic continuing to grow, responsive design isn\'t optional — it\'s essential. In this post, we explore the latest techniques and best practices for building websites that work beautifully on every device.</p>',
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
