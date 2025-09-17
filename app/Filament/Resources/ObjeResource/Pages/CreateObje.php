<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;

class CreateObje extends CreateRecord
{
    protected static string $resource = ObjeResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('filament.resources.obje.notifications.created');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('filament.resources.obje.actions.create'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label(__('filament.resources.obje.actions.create_another'))
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('gray');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament.resources.obje.actions.cancel'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }

    public function getTitle(): string
    {
        return __('filament.resources.obje.pages.create_title');
    }

    protected function afterCreate(): void
    {
        // Media yükleme sonrası log kaydı
        if ($this->record) {
            $mediaCount = $this->record->getMedia('images')->count();
            Log::info('Obje oluşturuldu', [
                'obje_id' => $this->record->id,
                'obje_name' => $this->record->name,
                'media_sayisi' => $mediaCount
            ]);
        }
    }
}
