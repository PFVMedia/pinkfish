<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('meta_description')
                    ->label('Meta description')
                    ->helperText('Used for search engine snippets and social shares. Aim for 140–160 characters.')
                    ->maxLength(255)
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('og_image')
                    ->label('Social share image')
                    ->image()
                    ->directory('blog-og')
                    ->helperText('Recommended 1200×630.')
                    ->columnSpanFull(),
                DatePicker::make('published_at')
                    ->required(),
                Toggle::make('is_published')
                    ->default(true),
            ]);
    }
}
