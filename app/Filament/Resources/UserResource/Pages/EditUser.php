<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Kullanıcıyı Sil')
                ->modalDescription('Bu kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')
                ->modalSubmitActionLabel('Evet, Sil'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Kullanıcı güncellendi')
            ->body('Kullanıcı bilgileri başarıyla güncellendi.');
    }
}
