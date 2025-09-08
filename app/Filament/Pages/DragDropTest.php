<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Obje;
use App\Models\Oneri;
use App\Models\ProjectDesign;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DragDropTest extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.pages.drag-drop-test';

    protected static ?string $title = 'Landscape Designer';

    protected static ?string $navigationLabel = 'Peyzaj Tasarımcısı';

    protected static bool $shouldRegisterNavigation = true; // Bu sayfayı navigation'da göster

    public ?string $projectImage = null;
    public ?int $projectId = null;
    public ?Oneri $project = null;

    public function mount(): void
    {
        // Sadece yetkili kullanıcılar erişebilsin
        if (!Auth::check()) {
            Log::warning('🚨 [AUTH] Kullanıcı giriş yapmamış, erişim reddedildi');
            abort(403);
        }

        Log::info('🔐 [AUTH] Kullanıcı girişi doğrulandı', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);

        // URL'den parametreleri al
        $this->projectImage = request()->get('image');
        $this->projectId = request()->get('project_id');

        Log::info('📥 [MOUNT] URL parametreleri alındı', [
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage
        ]);

        // Proje varsa yükle
        if ($this->projectId) {
            $this->project = Oneri::find($this->projectId);

            if (!$this->project) {
                Log::error('❌ [MOUNT] Proje bulunamadı', ['project_id' => $this->projectId]);
                abort(404, 'Proje bulunamadı');
            }

            Log::info('✅ [MOUNT] Proje yüklendi', [
                'project_id' => $this->project->id,
                'project_title' => $this->project->title
            ]);

            // Proje resmi URL'ini güncelle
            if ($this->project->hasMedia('images')) {
                $this->projectImage = $this->project->getFirstMediaUrl('images');
                Log::info('🖼️ [MOUNT] Proje resmi bulundu', ['image_url' => $this->projectImage]);
            } else {
                Log::info('📷 [MOUNT] Proje resmi bulunamadı');
            }
        } else {
            Log::info('ℹ️ [MOUNT] Project ID belirtilmemiş');
        }
    }

    public function getViewData(): array
    {
        Log::info('📊 [VIEW DATA] getViewData çağrıldı');

        // Kategorileri çek - sadece Obje modelindeki sabit kategoriler
        $kategoriler = [];

        // Sadece Obje modelindeki sabit kategorileri kullan
        foreach (Obje::CATEGORIES as $key => $name) {
            $kategoriler[] = [
                'id' => $key,
                'name' => $name,
                'type' => 'static' // Sabit kategori
            ];
        }

        Log::info('📂 [VIEW DATA] Kategoriler hazırlandı', ['count' => count($kategoriler)]);

        // Objeler tablosundan tüm objeleri çek
        $objeler = Obje::all()->map(function ($obje) {
            return [
                'id' => $obje->id,
                'name' => $obje->name, // Yeni İngilizce sütun adı
                'category' => $obje->category, // Yeni İngilizce sütun adı
                'image_url' => $obje->hasMedia('images') ? $obje->getFirstMediaUrl('images') : null,
            ];
        });

        Log::info('🗂️ [VIEW DATA] Objeler hazırlandı', ['count' => $objeler->count()]);

        // Mevcut tasarımı yükle (eğer varsa)
        $existingDesign = null;
        
        if ($this->project) {
            // Önce Project modelindeki design_landscape alanını kontrol et
            if ($this->project->design_landscape) {
                $existingDesign = $this->project->design_landscape;
                Log::info('🎨 [VIEW DATA] Mevcut tasarım Project.design_landscape\'dan yüklendi');
            }
            // Eğer yoksa, eski ProjectDesign tablosunu kontrol et
            elseif ($this->project->design && $this->project->design->design_data) {
                $existingDesign = $this->project->design->design_data;
                Log::info('🎨 [VIEW DATA] Mevcut tasarım ProjectDesign.design_data\'dan yüklendi');
            } else {
                Log::info('🆕 [VIEW DATA] Mevcut tasarım bulunamadı');
            }
        }

        $viewData = [
            'objeler' => $objeler,
            'kategoriler' => $kategoriler,
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'existing_design' => $existingDesign,
            'project' => $this->project,
        ];

        Log::info('✅ [VIEW DATA] View data hazırlandı', [
            'objeler_count' => $objeler->count(),
            'kategoriler_count' => count($kategoriler),
            'project_id' => $this->projectId,
            'has_existing_design' => !is_null($existingDesign)
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
                ->url(url('/admin/oneris')),

            Action::make('saveDesign')
                ->label('Tasarımı Kaydet ve Tamamla')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(fn() => $this->saveDesignData()),
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
            console.log("🚀 [SAVE] Tasarım kaydetme işlemi başladı");
            console.log("📊 [SAVE] designElements array:", designElements);
            
            if (!designElements || designElements.length === 0) {
                console.warn("⚠️ [SAVE] Tasarımda hiç element yok!");
                alert("Kaydetmek için önce tasarıma element eklemelisiniz!");
                return;
            }
            
            const elements = designElements.map(element => ({
                obje_id: element.obje_id,
                x: element.x,
                y: element.y,
                width: element.width,
                height: element.height,
                rotation: element.rotation || 0,
                aspectRatio: element.aspectRatio || 1,
                scale: element.scale
            }));
            
            console.log("📦 [SAVE] Hazırlanan elements:", elements);

            const design = {
                project_id: ' . $this->project->id . ',
                elements: elements,
                timestamp: new Date().toISOString(),
                total_elements: elements.length,
                metadata: {
                    elementsWithRotation: elements.filter(el => el.rotation && el.rotation !== 0).length,
                    totalRotation: elements.reduce((sum, el) => sum + Math.abs(el.rotation || 0), 0)
                }
            };
            
            console.log("💾 [SAVE] Final tasarım verisi:", design);
            console.log("📞 [SAVE] storeDesignData çağrılıyor...");

            $wire.call("storeDesignData", design);
        ');
    }

    public function storeDesignData($designData)
    {
        try {
            Log::info('🚀 [BACKEND SAVE] Tasarım kaydetme başladı', [
                'project_id' => $this->project->id,
                'design_data_keys' => array_keys($designData ?? []),
                'elements_count' => isset($designData['elements']) ? count($designData['elements']) : 0
            ]);

            if (!$designData || !isset($designData['elements']) || empty($designData['elements'])) {
                \Filament\Notifications\Notification::make()
                    ->title('Hata!')
                    ->body('Tasarım verisi boş! Önce tasarıma element eklemelisiniz.')
                    ->warning()
                    ->send();
                return;
            }

            $elementsCount = is_array($designData) && isset($designData['total_elements']) ? $designData['total_elements'] : 0;

            Log::info('💾 [BACKEND SAVE] Veritabanına kaydediliyor', [
                'elements_count' => $elementsCount,
                'design_data' => $designData
            ]);

            // Tasarım verilerini Project modeline kaydet (yeni yaklaşım)
            $this->project->update(['design_landscape' => $designData]);

            // Tasarım verilerini ProjectDesign tablosuna da kaydet (geriye uyumluluk için)
            ProjectDesign::updateOrCreate(
                ['project_id' => $this->project->id],
                ['design_data' => $designData]
            );

            // Projeyi tamamlanmış olarak işaretle
            $this->project->update(['design_completed' => true]);

            Log::info('✅ [BACKEND SAVE] Tasarım başarıyla kaydedildi', [
                'project_id' => $this->project->id,
                'elements_count' => $elementsCount
            ]);

            // Başarı mesajı ve yönlendirme
            \Filament\Notifications\Notification::make()
                ->title('Başarılı!')
                ->body("Tasarım başarıyla kaydedildi! {$elementsCount} element ve rotasyon verileri dahil edildi.")
                ->success()
                ->send();

            $this->js("setTimeout(() => window.location.href = '/admin/oneris', 100);");

        } catch (\Exception $e) {
            Log::error('❌ [BACKEND SAVE] Tasarım kayıt hatası', [
                'error' => $e->getMessage(),
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
