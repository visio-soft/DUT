<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

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
        $actions = [
            Actions\DeleteAction::make(),
        ];
        
        // Eğer proje tasarımı tamamlandıysa düzenleme yerine tasarımı görüntüle butonu ekle
        if ($this->record && $this->record->design_completed && $this->record->design) {
            $designId = $this->record->design->id;
            $designUrl = "/admin/project-designs/{$designId}";

            $actions[] = Actions\Action::make('viewDesign')
                ->label('Tasarımı Görüntüle')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url($designUrl)
                ->openUrlInNewTab(false);
        }
        
        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // If a project already has a completed design, redirect edit attempts to the design view
    public function mount($record): void
    {
        parent::mount($record);

        if ($this->record && $this->record->design_completed && $this->record->design) {
            $designId = $this->record->design->id;
            redirect()->to(url("/admin/project-designs/{$designId}"))->send();
        }
    }
}
