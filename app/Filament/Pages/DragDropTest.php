<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Obje;
use App\Models\Project;
use App\Models\ProjectDesign;
use Illuminate\Support\Facades\Auth;

class DragDropTest extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.pages.drag-drop-test';

    protected static ?string $title = 'Landscape Designer';

    protected static ?string $navigationLabel = 'Peyzaj Tasarımcısı';

    protected static bool $shouldRegisterNavigation = false; // Bu sayfayı navigation'da gösterme

    public ?string $projectImage = null;
    public ?int $projectId = null;
    public ?Project $project = null;

    public function mount(): void
    {
        // Sadece yetkili kullanıcılar erişebilsin
        if (!Auth::check()) {
            abort(403);
        }

        // URL'den parametreleri al
        $this->projectImage = request()->get('image');
        $this->projectId = request()->get('project_id');
        
        // Proje varsa yükle
        if ($this->projectId) {
            $this->project = Project::find($this->projectId);
            
            if (!$this->project) {
                abort(404, 'Proje bulunamadı');
            }
            
            // Proje resmi URL'ini güncelle
            if ($this->project->hasMedia('images')) {
                $this->projectImage = $this->project->getFirstMediaUrl('images');
            }
        }
    }

    public function getViewData(): array
    {
        // Objeler tablosundan tüm objeleri çek
        $objeler = Obje::all()->map(function ($obje) {
            $imageUrl = null;
            
            // Eğer objeye ait medya varsa URL'ini al
            if ($obje->hasMedia('images')) {
                $imageUrl = $obje->getFirstMediaUrl('images');
            }
            
            return [
                'id' => $obje->id,
                'isim' => $obje->isim,
                'image_url' => $imageUrl,
            ];
        });

        // Mevcut tasarımı yükle (eğer varsa)
        $existingDesign = null;
        if ($this->project && $this->project->design) {
            $existingDesign = $this->project->design->design_data;
        }

        return [
            'objeler' => $objeler,
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'existing_design' => $existingDesign,
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('goBack')
                ->label('Geri Dön')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(url('/admin/projects'))
                ->openUrlInNewTab(false),
                
            Action::make('saveDesign')
                ->label('Tasarımı Kaydet ve Tamamla')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Tasarımı Tamamla')
                ->modalDescription('Bu tasarımı kaydedip projeyi tamamlanmış olarak işaretlemek istediğinize emin misiniz?')
                ->action(function () {
                    $this->saveDesignData();
                }),
        ];
    }

    public function saveDesignData()
    {
        if (!$this->project) {
            \Filament\Notifications\Notification::make()
                ->title('Hata!')
                ->body('Proje bulunamadı!')
                ->danger()
                ->send();
            return;
        }

        // JavaScript'ten tasarım verilerini al ve kaydet
        $this->js('
            // Tasarım verilerini topla
            const elements = [];
            const boundary = document.getElementById("propertyBoundary");
            const landscapeElements = boundary.querySelectorAll(".landscape-element");

            landscapeElements.forEach(element => {
                const content = element.querySelector(".element-content");
                const image = content.querySelector("img");
                const imageUrl = image ? image.src : "";
                const name = image ? image.alt : "";
                
                // Obje ID\'sini al
                const objeId = element.getAttribute("data-obje-id");
                
                // Position\'u al
                const x = parseFloat(element.getAttribute("data-x")) || 0;
                const y = parseFloat(element.getAttribute("data-y")) || 0;
                
                // Size\'ı al
                const width = parseFloat(element.style.width) || 120;
                const height = parseFloat(element.style.height) || 120;

                elements.push({
                    id: element.id,
                    obje_id: objeId,
                    type: element.id.split("_")[1],
                    image_url: imageUrl,
                    name: name,
                    x: x,
                    y: y,
                    width: width,
                    height: height,
                    scale: {
                        x: width / 120,
                        y: height / 120
                    }
                });
            });

            const design = {
                project_id: ' . $this->project->id . ',
                elements: elements,
                timestamp: new Date().toISOString(),
                total_elements: elements.length
            };

            // Livewire metodunu çağır
            $wire.call("storeDesignData", design);
        ');
    }

    public function storeDesignData($designData)
    {
        try {
            // Tasarım verilerini kaydet veya güncelle
            ProjectDesign::updateOrCreate(
                ['project_id' => $this->project->id],
                ['design_data' => $designData]
            );

            // Projeyi tamamlanmış olarak işaretle
            $this->project->update(['design_completed' => true]);

            // Başarı mesajı
            \Filament\Notifications\Notification::make()
                ->title('Başarılı!')
                ->body('Tasarım başarıyla kaydedildi ve proje tamamlandı.')
                ->success()
                ->send();

            // 2 saniye bekle sonra yönlendir
            $this->js("
                setTimeout(function() {
                    window.location.href = '/admin/projects';
                }, 2000);
            ");

        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Hata!')
                ->body('Tasarım kaydedilirken bir hata oluştu: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getHeading(): string
    {
        return 'Peyzaj Tasarım Aracı';
    }

    public function getSubheading(): ?string
    {
        return 'Projeniz için peyzaj tasarımı oluşturun. Elementleri sürükleyip bırakarak tasarımınızı yapabilirsiniz.';
    }
}
