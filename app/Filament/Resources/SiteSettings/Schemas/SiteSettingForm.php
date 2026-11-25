<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public const ALLOWED_KEYS = [
        'site_name' => 'Site Name',
        'site_description' => 'Site Description',
        'logo_path' => 'Logo Path / URL',
        'favicon_path' => 'Favicon Path / URL',
        'footer_text' => 'Footer Text',
        'contact_email' => 'Contact Email',
        'contact_location' => 'Contact Location',
        'instagram_url' => 'Instagram URL',
        'youtube_url' => 'YouTube URL',
        'x_url' => 'X / Twitter URL',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->options(self::ALLOWED_KEYS)
                    ->in(array_keys(self::ALLOWED_KEYS))
                    ->searchable()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Long Text',
                        'email' => 'Email',
                        'url' => 'URL',
                        'media' => 'Media Path',
                    ])
                    ->default('text')
                    ->required(),
                Textarea::make('value')
                    ->rows(4)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
