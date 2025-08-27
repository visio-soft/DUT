<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Obje;
use App\Models\Project;
use App\Models\ProjectDesign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        Log::info('📊 [VIEW DATA] getViewData metodu çağrıldı');

        // Objeler tablosundan tüm objeleri çek
        $objeler = Obje::all()->map(function ($obje) {
            $imageUrl = null;
            if ($obje->hasMedia('images')) {
                $imageUrl = $obje->getFirstMediaUrl('images');
            }
            return [
                'id' => $obje->id,
                'name' => $obje->name,
                'image_url' => $imageUrl,
            ];
        });

        Log::info('🗂️ [VIEW DATA] Objeler yüklendi:', [
            'obje_count' => $objeler->count()
        ]);

        // Mevcut tasarımı yükle (eğer varsa)
        $existingDesign = null;
        if ($this->project && $this->project->design) {
            $existingDesign = $this->project->design->design_data;
            Log::info('🎨 [VIEW DATA] Mevcut tasarım bulundu:', [
                'design_elements_count' => isset($existingDesign['elements']) ? count($existingDesign['elements']) : 0
            ]);
        } else {
            Log::info('🆕 [VIEW DATA] Mevcut tasarım bulunamadı');
        }

        $viewData = [
            'objeler' => $objeler,
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'existing_design' => $existingDesign,
        ];

        Log::info('✅ [VIEW DATA] View data hazırlandı:', [
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'has_existing_design' => $existingDesign !== null
        ]);

        return $viewData;
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
                // Do not show a confirmation modal; run immediately when clicked
                ->action(function () {
                    $this->saveDesignData();
                }),
        ];
    }    public function saveDesignData()
    {
        Log::info('🎯 [SAVE DESIGN] Tasarım kaydetme işlemi başlatıldı');

        if (!$this->project) {
            Log::error('❌ [SAVE DESIGN] Proje bulunamadı!');
            \Filament\Notifications\Notification::make()
                ->title('Hata!')
                ->body('Proje bulunamadı!')
                ->danger()
                ->send();
            return;
        }

        Log::info('📊 [SAVE DESIGN] Proje bilgileri:', [
            'project_id' => $this->project->id,
            'project_title' => $this->project->title
        ]);

        // JavaScript'ten tasarım verilerini al
        $this->js('
            console.log("💾 [SAVE] Tasarım kaydetme işlemi başlatılıyor...");

            // Global designElements array\'ini kullan
            const elements = designElements.map((element, index) => {
                console.log(`   📦 [SAVE] Element ${index + 1}:`, element);
                return {
                    obje_id: element.obje_id,
                    x: element.x,
                    y: element.y,
                    width: element.width,
                    height: element.height,
                    scale: element.scale
                };
            });

            const design = {
                project_id: ' . $this->project->id . ',
                elements: elements,
                timestamp: new Date().toISOString(),
                total_elements: elements.length
            };

            console.log("📊 [SAVE] Kaydedilecek tasarım verisi:", design);
            console.log("📈 [SAVE] Element sayısı:", design.total_elements);

            // Livewire metodunu çağır
            console.log("📞 [SAVE] Livewire storeDesignData metodu çağrılıyor...");
            $wire.call("storeDesignData", design);
        ');
    }

    public function storeDesignData($designData)
    {
        Log::info('[STORE] storeDesignData metodu çağrıldı', [
            'design_data' => $designData
        ]);

        try {
            Log::info('[STORE] Database işlemi başlatılıyor...');

            // Tasarım verilerini kaydet veya güncelle
            $projectDesign = ProjectDesign::updateOrCreate(
                ['project_id' => $this->project->id],
                ['design_data' => $designData]
            );

            $elementsCount = is_array($designData) && isset($designData['total_elements']) ? $designData['total_elements'] : 0;

            Log::info('[STORE] ProjectDesign kaydedildi:', [
                'project_design_id' => $projectDesign->id,
                'project_id' => $this->project->id,
                'elements_count' => $elementsCount
            ]);

            // Projeyi tamamlanmış olarak işaretle
            $this->project->update(['design_completed' => true]);

            Log::info('[STORE] Proje tamamlandı olarak işaretlendi');

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
                }, 100);
            ");

        } catch (\Exception $e) {
            Log::error('[STORE] Tasarım kaydetme hatası:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            \Filament\Notifications\Notification::make()
                ->title('Hata!')
                ->body('Tasarım kaydedilirken bir hata oluştu: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getHeading(): string
    {
        return 'Tasarım Aracı';
    }

    public function getSubheading(): ?string
    {
        return 'Projeniz için tasarımı oluşturun. Elementleri sürükleyip bırakarak tasarımınızı yapabilirsiniz.';
    }
}
