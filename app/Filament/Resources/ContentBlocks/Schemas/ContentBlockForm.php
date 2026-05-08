<?php

namespace App\Filament\Resources\ContentBlocks\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContentBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required()
                    ->helperText('Human-readable name shown in the admin only.'),
                TextInput::make('key')
                    ->required()
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->helperText('Programmatic identifier used by the site code. Cannot be changed once created.'),
                Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'html' => 'HTML',
                        'image' => 'Image URL',
                        'boolean' => 'Boolean',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('value')
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['text', 'image'], true))
                    ->maxLength(2000),
                RichEditor::make('value')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('type') === 'html'),
                Toggle::make('value')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('type') === 'boolean'),
                Textarea::make('value')
                    ->columnSpanFull()
                    ->visible(fn ($get) => ! in_array($get('type'), ['text', 'html', 'image', 'boolean'], true)),
            ]);
    }
}
