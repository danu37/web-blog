<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Models\Media;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action, Media $record): void {
                    if ($record->isReferenced()) {
                        Notification::make()
                            ->danger()
                            ->title('Cannot delete referenced media')
                            ->body('This image is still used by a post, gallery, setting, or homepage section.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $disk = $this->record->disk ?: 'public';
        $path = $data['file_path'];

        if ($path !== $this->record->file_path && Storage::disk($disk)->exists($path)) {
            $data['mime_type'] = Storage::disk($disk)->mimeType($path) ?: $this->record->mime_type;
            $data['file_size'] = Storage::disk($disk)->size($path) ?: $this->record->file_size;
            $data['file_name'] = $data['file_name'] ?: basename($path);
        }

        return $data;
    }
}
