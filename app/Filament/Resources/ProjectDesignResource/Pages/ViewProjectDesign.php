<?php

namespace App\Filament\Resources\ProjectDesignResource\Pages;

use App\Filament\Resources\ProjectDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;

class ViewProjectDesign extends ViewRecord
{
    protected static string $resource = ProjectDesignResource::class;
    // The Blade file was moved to resources/views/filament/pages/view-project-design.blade.php
    protected static string $view = 'filament.pages.view-project-design';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function getTitle(): string
    {
        return __('filament.resources.project_design.page_titles.view') . ' ' . $this->record->project->title;
    }


    public function getViewData(): array
    {
        $record = $this->record;

        // Design data'yı hazırla
        // Bazı kayıtlar veritabanında JSON string olarak saklanmış olabilir.
        // Bu nedenle önce ham değeri alıp, string ise json_decode ile diziye çeviriyoruz.
        $rawDesign = $record->design_data ?? [];
        if (is_string($rawDesign)) {
            $decoded = json_decode($rawDesign, true);
            $designData = is_array($decoded) ? $decoded : [];
        } elseif (is_array($rawDesign)) {
            $designData = $rawDesign;
        } else {
            $designData = [];
        }

        // Proje arka plan resmini al (doğrudan - MediaHelper hatası var)
        $projectBackgroundImage = '';
        if ($record->project && $record->project->hasMedia('images')) {
            $projectBackgroundImage = $record->project->getFirstMediaUrl('images');
        }

        // Obje verilerini veritabanından çek (doğrudan - MediaHelper sorunu var)
        $objectData = [];
        if (isset($designData['elements']) && is_array($designData['elements'])) {
            $objeIds = array_unique(array_column($designData['elements'], 'obje_id'));

            foreach ($objeIds as $objeId) {
                $obje = \App\Models\Obje::find($objeId);
                if ($obje) {
                    // Doğrudan medya URL'si al (MediaHelper'sız)
                    $imageUrl = $obje->hasMedia('images') ? $obje->getFirstMediaUrl('images') : null;

                    $objectData[$objeId] = [
                        'id' => $obje->id,
                        'name' => $obje->name,
                        'image_url' => $imageUrl ?: "https://picsum.photos/120/120?random={$objeId}"
                    ];
                }
            }
        }

        // Kategorileri çek - sadece Obje modelindeki sabit kategoriler
        $kategoriler = [];

        // Sadece Obje modelindeki sabit kategorileri kullan
        foreach (\App\Models\Obje::CATEGORIES as $key => $name) {
            $kategoriler[] = [
                'id' => $key,
                'name' => $name,
                'type' => 'static'
            ];
        }

        // Objeler tablosundan tüm objeleri çek (DragDropTest gibi - MediaHelper yerine doğrudan)
        $objeler = \App\Models\Obje::all()->map(function ($obje) {
            return [
                'id' => $obje->id,
                'name' => $obje->name,
                'category' => $obje->category,
                'image_url' => $obje->hasMedia('images') ? $obje->getFirstMediaUrl('images') : null,
            ];
        });

        $project_id = $record->project->id ?? $record->project_id ?? null;
        $project_image = $projectBackgroundImage ?: null;
        $existing_design = $designData ?: null;

        Log::info('ViewProjectDesign: Design data prepared', [
            'design_data' => $designData,
            'object_data' => $objectData,
            'project_background' => $projectBackgroundImage,
            'objeler_count' => $objeler->count(),
            'objeler_sample' => $objeler->take(3)->toArray(), // İlk 3 objeyi örnek olarak göster
            'kategoriler_count' => count($kategoriler),
        ]);

        return [
            // Orijinal camelCase veriler (diğer kodlar için)
            'designData' => $designData,
            'objectData' => $objectData,
            'projectBackgroundImage' => $projectBackgroundImage,
            'record' => $record,

            // Blade tarafında kullanılan snake_case / Turkish değişkenler (DragDropTest formatında)
            'objeler' => $objeler,
            'kategoriler' => $kategoriler,
            'project_id' => $project_id,
            'project_image' => $project_image,
            'existing_design' => $existing_design,
            'project' => $record->project,
        ];
    }
}
