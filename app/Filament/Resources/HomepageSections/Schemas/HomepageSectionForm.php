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
                        'editorial_intro' => 'Editorial Intro (Arch Portrait)',
                        'projects' => 'Selected Shop / Projects',
                        'featured_post' => 'Featured Article',
                        'article_grid' => 'Article Grid (3:4 Portrait)',
                        'gallery_strip' => 'Gallery Strip',
                        'category_digest' => 'Category Digest & Latest',
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
                    ->helperText('Supported keys: eyebrow, background_image, portrait_image, secondary_description, cta_label, cta_url, items (JSON array of {title, price_label, price_value, description, image_url, link_url}).')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
