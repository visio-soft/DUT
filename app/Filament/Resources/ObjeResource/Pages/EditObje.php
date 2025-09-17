<?php

namespace App\Filament\Resources\ObjeResource\Pages;

use App\Filament\Resources\ObjeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;

class EditObje extends EditRecord
{
    protected static string $resource = ObjeResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return __('filament.resources.obje.notifications.updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label(__('filament.resources.obje.actions.view'))
                ->icon('heroicon-o-eye')
                ->color('info'),
            Actions\DeleteAction::make()
                ->label(__('filament.resources.obje.actions.delete'))
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament.resources.obje.actions.save'))
            ->submit('save')
            ->keyBindings(['mod+s']);
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
        return __('filament.resources.obje.pages.edit_title');
    }

    protected function afterSave(): void
    {
        // Media güncelleme sonrası log kaydı
        if ($this->record) {
            $mediaCount = $this->record->getMedia('images')->count();
            Log::info('Obje güncellendi', [
                'obje_id' => $this->record->id,
                'obje_name' => $this->record->name,
                'media_sayisi' => $mediaCount
            ]);
        }
    }
}
