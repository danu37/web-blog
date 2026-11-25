<?php

namespace App\Filament\Resources\Media\Tables;

use App\Models\Media;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Preview')
                    ->disk('public')
                    ->square(),
                TextColumn::make('file_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alt_text')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('MIME')
                    ->badge(),
                TextColumn::make('file_size')
                    ->label('Size')
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Uploaded By')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, Collection $records): void {
                            if ($records->contains(fn (Media $record): bool => $record->isReferenced())) {
                                Notification::make()
                                    ->danger()
                                    ->title('Cannot delete referenced media')
                                    ->body('One or more selected images are still referenced.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
