<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Pink Fish'],
            ['group' => 'general', 'key' => 'logo_text', 'value' => 'Pink Fish'],
            ['group' => 'general', 'key' => 'brand_tagline', 'value' => 'Building modern web applications with precision and care.'],
            ['group' => 'general', 'key' => 'copyright_text', 'value' => 'Pink Fish Ventures Inc. All rights reserved.'],
            ['group' => 'general', 'key' => 'color_palette', 'value' => 'indigo'],
            ['group' => 'general', 'key' => 'background_style', 'value' => 'orbs'],
            ['group' => 'navigation', 'key' => 'main_nav', 'value' => [
                ['name' => 'Services', 'href' => '/services'],
                ['name' => 'Tools', 'href' => '/tools'],
                ['name' => 'Blog', 'href' => '/blog'],
                ['name' => 'Links', 'href' => '/links'],
            ]],
            ['group' => 'navigation', 'key' => 'cta_text', 'value' => 'Get Started'],
            ['group' => 'navigation', 'key' => 'cta_url', 'value' => '/contact'],
            ['group' => 'footer', 'key' => 'footer_columns', 'value' => [
                [
                    'title' => 'Navigation',
                    'links' => [
                        ['name' => 'About', 'href' => '/about'],
                        ['name' => 'Services', 'href' => '/services'],
                        ['name' => 'Tools', 'href' => '/tools'],
                        ['name' => 'Blog', 'href' => '/blog'],
                    ],
                ],
                [
                    'title' => 'Resources',
                    'links' => [
                        ['name' => 'Links', 'href' => '/links'],
                        ['name' => 'Contact', 'href' => '/contact'],
                    ],
                ],
                [
                    'title' => 'Legal',
                    'links' => [
                        ['name' => 'Privacy Policy', 'href' => '/privacy'],
                        ['name' => 'Disclaimer', 'href' => '/disclaimer'],
                    ],
                ],
            ]],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
