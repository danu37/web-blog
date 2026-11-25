<?php

namespace App\Filament\Resources\HomepageSections\Schemas;

use App\Models\HomepageSection;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HomepageSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('section_key')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->options([
                        'hero' => 'Hero / Masthead',
                        'featured_post' => 'Featured Article',
                        'editorial_intro' => 'Editorial Intro',
                        'projects' => 'Selected Projects / Shop',
                        'article_grid' => 'Article Grid',
                        'gallery_strip' => 'Gallery Strip',
                    ])
                    ->in(HomepageSection::ALLOWED_TYPES)
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active on Homepage')
                    ->default(true)
                    ->required(),
                Textarea::make('description')
                    ->rows(3)
                    ->default(null)
                    ->columnSpanFull(),
                KeyValue::make('settings')
                    ->label('Section Settings')
                    ->keyLabel('Setting Key')
                    ->valueLabel('Value')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
