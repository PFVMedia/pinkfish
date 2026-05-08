<?php

namespace App\Filament\Resources\ContentBlocks\Schemas;

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
                    ->label('Value')
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['text', 'image'], true))
                    ->maxLength(2000),
                Textarea::make('value')
                    ->label('Value (HTML)')
                    ->helperText('Raw HTML — use tags like <p>, <br>, <strong>.')
                    ->rows(10)
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('type') === 'html'),
                Toggle::make('value')
                    ->label('Value')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('type') === 'boolean'),
            ]);
    }
}
