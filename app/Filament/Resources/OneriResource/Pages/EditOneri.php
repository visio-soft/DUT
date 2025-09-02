<?php

namespace App\Filament\Resources\OneriResource\Pages;

use App\Filament\Resources\OneriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOneri extends EditRecord
{
    protected static string $resource = OneriResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Güncelle'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [Actions\DeleteAction::make()];

        // Eğer öneri tasarımı tamamlandıysa tasarımı görüntüle butonu ekle
        if ($this->record && $this->record->design_completed && $this->record->design) {
            $actions[] = Actions\Action::make('viewDesign')
                ->label('Tasarımı Görüntüle')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url("/admin/project-designs/{$this->record->design->id}");
        }

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
