<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;

class PageBlocks
{
    public static function all(): array
    {
        return [
            self::hero(),
            self::richText(),
            self::cardGrid(),
            self::statsPanel(),
            self::workflow(),
            self::cta(),
            self::blogLatest(),
            self::contactForm(),
            self::image(),
            self::pageHeader(),
            self::modelList(),
        ];
    }

    public static function hero(): Block
    {
        return Block::make('hero')
            ->label('Hero Section')
            ->icon(Heroicon::OutlinedSparkles)
            ->schema([
                TextInput::make('badge_text')
                    ->label('Badge Text'),
                TextInput::make('heading')
                    ->required(),
                Textarea::make('subtitle'),
                Repeater::make('buttons')
                    ->schema([
                        TextInput::make('text')->required(),
                        TextInput::make('url')->required(),
                        Select::make('style')
                            ->options(['primary' => 'Primary', 'outline' => 'Outline'])
                            ->default('primary'),
                    ])
                    ->maxItems(3)
                    ->defaultItems(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function richText(): Block
    {
        return Block::make('rich_text')
            ->label('Rich Text')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function cardGrid(): Block
    {
        return Block::make('card_grid')
            ->label('Card Grid')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                TextInput::make('badge_text')
                    ->label('Badge Text'),
                TextInput::make('heading')
                    ->required(),
                Textarea::make('subheading'),
                Select::make('columns')
                    ->options([2 => '2 Columns', 3 => '3 Columns', 4 => '4 Columns'])
                    ->default(3),
                Repeater::make('cards')
                    ->schema([
                        Select::make('icon')
                            ->options(self::iconOptions())
                            ->searchable(),
                        TextInput::make('title')->required(),
                        Textarea::make('description')->required(),
                        TextInput::make('url')
                            ->label('Link URL'),
                    ])
                    ->defaultItems(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function statsPanel(): Block
    {
        return Block::make('stats_panel')
            ->label('Stats Panel')
            ->icon(Heroicon::OutlinedChartBar)
            ->schema([
                TextInput::make('heading'),
                Repeater::make('stats')
                    ->schema([
                        Select::make('icon')
                            ->options(self::iconOptions())
                            ->searchable(),
                        TextInput::make('label')->required(),
                        TextInput::make('value')->required(),
                        TextInput::make('badge_text')
                            ->label('Badge'),
                    ])
                    ->defaultItems(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function workflow(): Block
    {
        return Block::make('workflow')
            ->label('Workflow Section')
            ->icon(Heroicon::OutlinedCog6Tooth)
            ->schema([
                TextInput::make('badge_text')
                    ->label('Badge Text'),
                TextInput::make('heading')
                    ->required(),
                Repeater::make('panels')
                    ->schema([
                        Select::make('icon')
                            ->options(self::iconOptions())
                            ->searchable(),
                        TextInput::make('title')->required(),
                        Textarea::make('description'),
                        Select::make('style')
                            ->options(['preview' => 'Code Preview', 'checklist' => 'Checklist'])
                            ->default('preview'),
                        Repeater::make('checklist_items')
                            ->schema([TextInput::make('text')->required()])
                            ->visible(fn ($get) => $get('style') === 'checklist')
                            ->columnSpanFull(),
                    ])
                    ->defaultItems(2)
                    ->maxItems(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function cta(): Block
    {
        return Block::make('cta')
            ->label('Call to Action')
            ->icon(Heroicon::OutlinedMegaphone)
            ->schema([
                TextInput::make('heading')
                    ->required(),
                Textarea::make('body'),
                Repeater::make('buttons')
                    ->schema([
                        TextInput::make('text')->required(),
                        TextInput::make('url')->required(),
                        Select::make('style')
                            ->options(['primary' => 'Primary', 'outline' => 'Outline'])
                            ->default('primary'),
                    ])
                    ->maxItems(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function blogLatest(): Block
    {
        return Block::make('blog_latest')
            ->label('Latest Blog Posts')
            ->icon(Heroicon::OutlinedNewspaper)
            ->schema([
                TextInput::make('badge_text')
                    ->label('Badge Text')
                    ->default('Blog'),
                TextInput::make('heading')
                    ->default('Latest insights'),
                Select::make('count')
                    ->options([3 => '3 Posts', 4 => '4 Posts', 6 => '6 Posts'])
                    ->default(3),
            ]);
    }

    public static function contactForm(): Block
    {
        return Block::make('contact_form')
            ->label('Contact Form')
            ->icon(Heroicon::OutlinedEnvelope)
            ->schema([
                TextInput::make('badge_text')
                    ->label('Badge Text')
                    ->default('Contact us'),
                TextInput::make('heading')
                    ->default("Let's talk"),
                Textarea::make('subtitle'),
                Toggle::make('show_contact_info')
                    ->label('Show contact info cards')
                    ->default(true),
            ]);
    }

    public static function image(): Block
    {
        return Block::make('image')
            ->label('Image')
            ->icon(Heroicon::OutlinedPhoto)
            ->schema([
                FileUpload::make('image')
                    ->image()
                    ->directory('page-images')
                    ->required(),
                TextInput::make('alt_text')
                    ->label('Alt Text')
                    ->required()
                    ->helperText('Describe the image for screen readers and search engines. Required for accessibility and SEO.'),
                TextInput::make('caption'),
            ]);
    }

    public static function pageHeader(): Block
    {
        return Block::make('page_header')
            ->label('Page Header')
            ->icon(Heroicon::OutlinedH1)
            ->schema([
                TextInput::make('badge_text')
                    ->label('Badge Text'),
                TextInput::make('heading')
                    ->required(),
                Textarea::make('subheading'),
            ]);
    }

    public static function modelList(): Block
    {
        return Block::make('model_list')
            ->label('Dynamic Model List')
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                Select::make('model_type')
                    ->options(['tools' => 'Tools', 'links' => 'Links'])
                    ->required(),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public static function iconOptions(): array
    {
        return [
            'Code2' => 'Code',
            'Globe' => 'Globe',
            'Palette' => 'Palette',
            'Server' => 'Server',
            'Shield' => 'Shield',
            'Zap' => 'Zap',
            'Sparkles' => 'Sparkles',
            'Mail' => 'Mail',
            'MapPin' => 'Map Pin',
            'Phone' => 'Phone',
            'ExternalLink' => 'External Link',
            'ArrowRight' => 'Arrow Right',
            'BarChart3' => 'Bar Chart',
            'Users' => 'Users',
            'CheckCircle' => 'Check Circle',
            'Wrench' => 'Wrench',
            'Rocket' => 'Rocket',
            'Heart' => 'Heart',
            'Star' => 'Star',
            'Lock' => 'Lock',
            'Database' => 'Database',
            'Cloud' => 'Cloud',
            'Monitor' => 'Monitor',
            'Cpu' => 'CPU',
            'Layout' => 'Layout',
        ];
    }
}
