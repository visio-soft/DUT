<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();
        $data['design_completed'] = false; // Başlangıçta tasarım tamamlanmamış
        
        return $data;
    }
    
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Oluştur')
                ->hidden(), // Normal oluştur butonunu gizle
            $this->getCreateAnotherFormAction()
                ->label('Oluştur ve Yeni')
                ->hidden(), // Normal oluştur ve yeni butonunu gizle
            
            // Yeni özel buton - Tasarıma Başla
            \Filament\Actions\Action::make('createAndDesign')
                ->label('Tasarıma Başla')
                ->icon('heroicon-o-paint-brush')
                ->color('success')
                ->size('lg')
                ->action(function () {
                    try {
                        Log::info('Tasarıma Başla butonuna tıklandı');
                        
                        // Form validasyonunu çalıştır ve kaydı oluştur
                        $this->create();
                        
                        Log::info('Proje oluşturuldu', ['project_id' => $this->record->id]);
                        
                        // Proje kaydedildikten sonra direkt yönlendirme yap
                        $project = $this->record;
                        
                        // Proje resminin URL'ini al
                        $imageUrl = '';
                        if ($project && $project->hasMedia('images')) {
                            $imageUrl = $project->getFirstMediaUrl('images');
                        }
                        
                        // Tasarım sayfasına yönlendir
                        $designUrl = "/admin/drag-drop-test?project_id={$project->id}&image=" . urlencode($imageUrl);
                        
                        Log::info('Yönlendirme URL', ['url' => $designUrl]);
                        
                        // Livewire redirect kullan
                        return redirect($designUrl);
                    } catch (\Exception $e) {
                        Log::error('Tasarıma başla hatası', ['error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->extraAttributes([
                    'class' => 'w-full justify-center',
                    'x-data' => '{}',
                    'x-init' => '
                        const requiredFields = ["data.category_id", "data.title", "data.description", "data.start_date", "data.end_date", "data.budget", "data.district", "data.image"];
                        
                        const checkFields = () => {
                            let allFilled = true;
                            
                            requiredFields.forEach(field => {
                                const value = $wire.get(field);
                                if (!value || (Array.isArray(value) && value.length === 0)) {
                                    allFilled = false;
                                }
                            });
                            
                            // Mahalle kontrolü
                            const neighborhood = $wire.get("data.neighborhood");
                            const neighborhoodCustom = $wire.get("data.neighborhood_custom");
                            if (!neighborhood || (neighborhood === "__other" && !neighborhoodCustom)) {
                                allFilled = false;
                            }
                            
                            $el.disabled = !allFilled;
                        };
                        
                        // İlk kontrolü yap
                        checkFields();
                        
                        // Form değişikliklerini dinle
                        $wire.on("refresh", checkFields);
                        setInterval(checkFields, 1000);
                    '
                ]),
                
            $this->getCancelFormAction(),
        ];
    }
    

    
    protected function getRedirectUrl(): string
    {
        // Normal redirect'i devre dışı bırak çünkü afterCreate'de özel yönlendirme yapıyoruz
        return $this->getResource()::getUrl('index');
    }
}
