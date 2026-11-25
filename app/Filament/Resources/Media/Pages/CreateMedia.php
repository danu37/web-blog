<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $disk = 'public';
        $path = $data['file_path'];

        $data['user_id'] = auth()->id();
        $data['disk'] = $disk;
        $data['file_name'] = basename($path);
        $data['mime_type'] = Storage::disk($disk)->mimeType($path) ?: 'application/octet-stream';
        $data['file_size'] = Storage::disk($disk)->size($path) ?: 0;

        return $data;
    }
}
