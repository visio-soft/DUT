<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Kategori başarıyla güncellendi!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Sil')
                ->requiresConfirmation(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Kaydet')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('İptal')
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }

    public function getTitle(): string
    {
        return 'Kategori Düzenle';
    }
}
