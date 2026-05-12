<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.manage-site-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        $this->form->fill([
            'site_name' => $settings['site_name'] ?? '',
            'logo_text' => $settings['logo_text'] ?? '',
            'brand_tagline' => $settings['brand_tagline'] ?? '',
            'main_nav' => $settings['main_nav'] ?? [],
            'cta_text' => $settings['cta_text'] ?? 'Get Started',
            'cta_url' => $settings['cta_url'] ?? '/contact',
            'footer_columns' => $settings['footer_columns'] ?? [],
            'copyright_text' => $settings['copyright_text'] ?? 'All rights reserved.',
            'color_palette' => $settings['color_palette'] ?? 'indigo',
            'background_style' => $settings['background_style'] ?? 'orbs',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('General')
                    ->schema([
                        TextInput::make('site_name')
                            ->required(),
                        TextInput::make('logo_text')
                            ->required(),
                        Textarea::make('brand_tagline'),
                        TextInput::make('copyright_text'),
                        Select::make('color_palette')
                            ->label('Color Palette')
                            ->options([
                                'indigo' => 'Indigo (Default)',
                                'blue' => 'Blue',
                                'sky' => 'Sky',
                                'teal' => 'Teal',
                                'emerald' => 'Emerald',
                                'amber' => 'Amber',
                                'orange' => 'Orange',
                                'rose' => 'Rose',
                                'pink' => 'Pink',
                                'purple' => 'Purple',
                                'slate' => 'Slate',
                            ])
                            ->default('indigo')
                            ->required(),
                        Select::make('background_style')
                            ->label('Background Style')
                            ->options([
                                'orbs' => 'Orbs (Spheres)',
                                'cubes' => 'Cubes (3D)',
                                'pastel' => 'Pastel (Solid Color)',
                            ])
                            ->default('orbs')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Navigation')
                    ->schema([
                        Repeater::make('main_nav')
                            ->label('Menu Items')
                            ->helperText('When empty, the public site falls back to default links (Services, Tools, Blog, Links). Click "Add to Menu Items" below to override with your own.')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('href')->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                        TextInput::make('cta_text')
                            ->label('CTA Button Text'),
                        TextInput::make('cta_url')
                            ->label('CTA Button URL'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Footer')
                    ->schema([
                        Repeater::make('footer_columns')
                            ->label('Footer Columns')
                            ->helperText('When empty, the public site falls back to default footer columns (Navigation, Resources, Legal). Click "Add to Footer Columns" below to override with your own.')
                            ->schema([
                                TextInput::make('title')->required(),
                                Repeater::make('links')
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        TextInput::make('href')->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0),
                            ])
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => $data['site_name']],
            ['group' => 'general', 'key' => 'logo_text', 'value' => $data['logo_text']],
            ['group' => 'general', 'key' => 'brand_tagline', 'value' => $data['brand_tagline']],
            ['group' => 'general', 'key' => 'copyright_text', 'value' => $data['copyright_text']],
            ['group' => 'navigation', 'key' => 'main_nav', 'value' => $data['main_nav']],
            ['group' => 'navigation', 'key' => 'cta_text', 'value' => $data['cta_text']],
            ['group' => 'navigation', 'key' => 'cta_url', 'value' => $data['cta_url']],
            ['group' => 'footer', 'key' => 'footer_columns', 'value' => $data['footer_columns']],
            ['group' => 'general', 'key' => 'color_palette', 'value' => $data['color_palette']],
            ['group' => 'general', 'key' => 'background_style', 'value' => $data['background_style']],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['group' => $setting['group'], 'value' => $setting['value']],
            );
        }

        Cache::forget('site_settings');

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
