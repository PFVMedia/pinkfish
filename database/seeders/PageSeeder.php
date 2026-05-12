<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'title' => 'Home',
            'slug' => 'home',
            'sort_order' => 0,
            'blocks' => [
                ['type' => 'hero', 'data' => [
                    'badge_text' => 'Simplify your workflow',
                    'heading' => 'Build something extraordinary',
                    'subtitle' => 'Streamline your digital presence with our intuitive, scalable development platform. Designed for ambitious businesses.',
                    'buttons' => [
                        ['text' => 'Get started', 'url' => '/contact', 'style' => 'primary'],
                        ['text' => 'Learn more', 'url' => '/services', 'style' => 'outline'],
                    ],
                ]],
                ['type' => 'stats_panel', 'data' => [
                    'heading' => 'Welcome back!',
                    'stats' => [
                        ['icon' => 'Globe', 'label' => 'Projects Delivered', 'value' => '247', 'badge_text' => '+12% this year'],
                        ['icon' => 'Code2', 'label' => 'Client Satisfaction', 'value' => '98.5%', 'badge_text' => '+3% this quarter'],
                    ],
                ]],
                ['type' => 'workflow', 'data' => [
                    'badge_text' => 'Our workflow',
                    'heading' => 'How we make your projects easier',
                    'panels' => [
                        [
                            'icon' => 'Zap',
                            'title' => 'Rapid Prototyping',
                            'description' => 'We start with a working prototype in days, not months. See your vision come to life quickly and iterate based on real feedback.',
                            'style' => 'preview',
                            'checklist_items' => [],
                        ],
                        [
                            'icon' => 'Shield',
                            'title' => 'Production Ready',
                            'description' => 'Every project ships with automated testing, CI/CD pipelines, monitoring, and documentation. Built for the long haul.',
                            'style' => 'checklist',
                            'checklist_items' => [
                                ['text' => 'Automated test suite'],
                                ['text' => 'CI/CD pipeline configured'],
                                ['text' => 'SSL & security headers'],
                                ['text' => 'Performance optimized'],
                            ],
                        ],
                    ],
                ]],
                ['type' => 'card_grid', 'data' => [
                    'badge_text' => 'What we offer',
                    'heading' => 'Everything you need to ship',
                    'subheading' => 'From concept to production, we handle every aspect of your web project.',
                    'columns' => 3,
                    'cards' => [
                        ['icon' => 'Code2', 'title' => 'Custom Development', 'description' => 'Full-stack applications built with Laravel and Vue.js, tailored to your exact specifications.'],
                        ['icon' => 'Globe', 'title' => 'Web Applications', 'description' => 'Scalable, performant web apps that handle thousands of users with ease.'],
                        ['icon' => 'Palette', 'title' => 'UI/UX Design', 'description' => 'Beautiful, intuitive interfaces that keep users engaged and coming back.'],
                        ['icon' => 'Server', 'title' => 'DevOps & Hosting', 'description' => 'Reliable infrastructure with automated deployments and 99.9% uptime.'],
                        ['icon' => 'Shield', 'title' => 'Security First', 'description' => 'Enterprise-grade security practices baked into every line of code.'],
                        ['icon' => 'Zap', 'title' => 'Performance', 'description' => 'Lightning-fast load times with optimized queries and smart caching.'],
                    ],
                ]],
                ['type' => 'blog_latest', 'data' => [
                    'badge_text' => 'Blog',
                    'heading' => 'Latest insights',
                    'count' => 3,
                ]],
                ['type' => 'cta', 'data' => [
                    'heading' => 'Ready to get started?',
                    'body' => "Let's talk about your next project. We'd love to help you build something amazing.",
                    'buttons' => [
                        ['text' => 'Start a project', 'url' => '/contact', 'style' => 'primary'],
                        ['text' => 'Learn about us', 'url' => '/about', 'style' => 'outline'],
                    ],
                ]],
            ],
        ]);

        Page::create([
            'title' => 'About',
            'slug' => 'about',
            'sort_order' => 1,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'badge_text' => 'About us',
                    'heading' => 'Who we are',
                ]],
                ['type' => 'rich_text', 'data' => [
                    'content' => '<p>Edit this placeholder from the admin area with your company story and values.</p>',
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Services',
            'slug' => 'services',
            'sort_order' => 2,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'badge_text' => 'Our services',
                    'heading' => 'What we build',
                    'subheading' => 'We offer a comprehensive range of web development services tailored to your business needs.',
                ]],
                ['type' => 'card_grid', 'data' => [
                    'columns' => 3,
                    'cards' => [
                        ['icon' => 'Code2', 'title' => 'Custom Web Applications', 'description' => 'Full-stack web applications built with Laravel, Vue.js, and modern technologies tailored to your business needs.'],
                        ['icon' => 'Globe', 'title' => 'E-Commerce Solutions', 'description' => 'Scalable online stores with secure payment processing, inventory management, and seamless user experiences.'],
                        ['icon' => 'Sparkles', 'title' => 'API Development', 'description' => 'RESTful APIs and integrations that connect your systems, automate workflows, and enable mobile applications.'],
                        ['icon' => 'Palette', 'title' => 'UI/UX Design', 'description' => 'User-centered design that combines aesthetics with functionality. Responsive, accessible, and delightful interfaces.'],
                        ['icon' => 'Server', 'title' => 'DevOps & Deployment', 'description' => 'Server provisioning, CI/CD pipelines, monitoring, and infrastructure management for reliable deployments.'],
                        ['icon' => 'Shield', 'title' => 'Consulting & Strategy', 'description' => 'Technical consulting to help you make informed decisions about architecture, technology stack, and project planning.'],
                    ],
                ]],
                ['type' => 'cta', 'data' => [
                    'heading' => 'Ready to start your project?',
                    'body' => 'Get in touch and let us help you build something amazing.',
                    'buttons' => [
                        ['text' => 'Start a project', 'url' => '/contact', 'style' => 'primary'],
                    ],
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Tools',
            'slug' => 'tools',
            'sort_order' => 3,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'badge_text' => 'Our toolkit',
                    'heading' => 'Tools we recommend',
                    'subheading' => 'A curated collection of tools and resources we use daily.',
                ]],
                ['type' => 'model_list', 'data' => [
                    'model_type' => 'tools',
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Links',
            'slug' => 'links',
            'sort_order' => 4,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'badge_text' => 'Resources',
                    'heading' => 'Useful links',
                    'subheading' => 'Frameworks, tools, and resources for modern web development.',
                ]],
                ['type' => 'model_list', 'data' => [
                    'model_type' => 'links',
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Blog',
            'slug' => 'blog-index',
            'sort_order' => 5,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'badge_text' => 'Blog',
                    'heading' => 'Insights & updates',
                    'subheading' => 'Tutorials, best practices, and updates from our team.',
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Contact',
            'slug' => 'contact',
            'sort_order' => 6,
            'blocks' => [
                ['type' => 'contact_form', 'data' => [
                    'badge_text' => 'Contact us',
                    'heading' => "Let's talk",
                    'subtitle' => "Have a project in mind? We'd love to hear from you.",
                    'show_contact_info' => true,
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy',
            'sort_order' => 7,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'heading' => 'Privacy Policy',
                ]],
                ['type' => 'rich_text', 'data' => [
                    'content' => '<p>Edit this placeholder from the admin area to describe how you handle visitor data.</p>',
                ]],
            ],
        ]);

        Page::create([
            'title' => 'Disclaimer',
            'slug' => 'disclaimer',
            'sort_order' => 8,
            'blocks' => [
                ['type' => 'page_header', 'data' => [
                    'heading' => 'Disclaimer',
                ]],
                ['type' => 'rich_text', 'data' => [
                    'content' => '<p>The information provided on this website is for general informational purposes only. While we strive to keep the information up to date and correct, we make no representations or warranties of any kind about the completeness, accuracy, reliability, or availability of the website or the information contained on it.</p>',
                ]],
            ],
        ]);
    }
}
