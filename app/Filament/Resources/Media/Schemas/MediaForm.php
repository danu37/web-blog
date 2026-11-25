<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('Image File')
                    ->disk('public')
                    ->directory(fn (): string => 'media/'.now()->format('Y/m'))
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('alt_text')
                    ->label('Alt Text')
                    ->maxLength(255)
                    ->helperText('Describe the image for accessibility and SEO.')
                    ->columnSpanFull(),
                TextInput::make('file_name')
                    ->label('Display File Name')
                    ->maxLength(255)
                    ->disabledOn('create')
                    ->dehydrated(fn (string $operation): bool => $operation === 'edit'),
            ]);
    }
}
