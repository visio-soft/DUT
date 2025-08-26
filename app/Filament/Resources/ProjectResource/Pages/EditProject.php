<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Filament\Support\Enums\MaxWidth;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    // Relation manager sekmelerini açıcaz
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        // Eğer proje tasarımı tamamlandıysa tabs açalım
        return $this->record && $this->record->design_completed;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Proje Bilgileri';
    }

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
        
        // Eğer proje tasarımı tamamlandıysa tasarımı düzenle butonu ekle
        if ($this->record && $this->record->design_completed && $this->record->hasMedia('images')) {
            $imageUrl = $this->record->getFirstMediaUrl('images');
            $designUrl = "/admin/drag-drop-test?project_id={$this->record->id}&image=" . urlencode($imageUrl);
            
            $actions[] = Actions\Action::make('editDesign')
                ->label('Tasarımı Düzenle')
                ->icon('heroicon-o-paint-brush')
                ->color('warning')
                ->url($designUrl)
                ->openUrlInNewTab(false);
        }
        
        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
