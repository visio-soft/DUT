<?php

namespace App\Filament\Resources\ProjectDesignResource\Pages;

use App\Filament\Resources\ProjectDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;

class ViewProjectDesign extends ViewRecord
{
    protected static string $resource = ProjectDesignResource::class;
    protected static string $view = 'filament.resources.project-design-resource.pages.view-project-design';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function getTitle(): string
    {
        return 'Proje Tasarımı: ' . $this->record->project->title;
    }

    public function getSubheading(): ?string
    {
        $designData = is_array($this->record->design_data) ? $this->record->design_data : [];
        $elementCount = $designData['total_elements'] ?? 0;
        $timestamp = $designData['timestamp'] ?? null;

        $subheading = "Element Sayısı: {$elementCount}";

        if ($timestamp) {
            $date = \Carbon\Carbon::parse($timestamp)->format('d.m.Y H:i');
            $subheading .= " • Oluşturulma: {$date}";
        }

        return $subheading;
    }

    public function getViewData(): array
    {
        $record = $this->record;

        // Design data'yı hazırla
        $designData = $record->design_data ?? [];

        // Proje arka plan resmini al
        $projectBackgroundImage = '';
        if ($record->project && $record->project->hasMedia('images')) {
            $projectBackgroundImage = $record->project->getFirstMediaUrl('images');
        }

        // Obje verilerini veritabanından çek
        $objectData = [];
        if (isset($designData['elements']) && is_array($designData['elements'])) {
            $objeIds = array_unique(array_column($designData['elements'], 'obje_id'));

            foreach ($objeIds as $objeId) {
                $obje = \App\Models\Obje::find($objeId);
                if ($obje) {
                    $imageUrl = '';
                    if ($obje->hasMedia('images')) {
                        $imageUrl = $obje->getFirstMediaUrl('images');
                    }

                    $objectData[$objeId] = [
                        'id' => $obje->id,
                        'name' => $obje->name,
                        'image_url' => $imageUrl ?: "https://picsum.photos/120/120?random={$objeId}"
                    ];
                }
            }
        }

        Log::info('ViewProjectDesign: Design data prepared', [
            'design_data' => $designData,
            'object_data' => $objectData,
            'project_background' => $projectBackgroundImage
        ]);

        return [
            'designData' => $designData,
            'objectData' => $objectData,
            'projectBackgroundImage' => $projectBackgroundImage,
            'record' => $record
        ];
    }
}
