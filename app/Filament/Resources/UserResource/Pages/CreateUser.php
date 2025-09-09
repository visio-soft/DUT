<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // E-posta otomatik doğrulanmış olarak ayarla
        $data['email_verified_at'] = now();
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Kullanıcı oluşturuldu')
            ->body('Yeni kullanıcı başarıyla oluşturuldu.');
    }
}
