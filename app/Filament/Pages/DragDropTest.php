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
        $this->projectId = request()->get('project_id') ?: request()->get('project'); // URL'den project parametresini de kabul et
        
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
        // Öncelikle projects.design_landscape alanını kontrol et
        if ($this->project && isset($this->project->design_landscape) && $this->project->design_landscape) {
            $existingDesign = $this->project->design_landscape;
        } elseif ($this->project && $this->project->design) {
            // Geriye dönük uyumluluk için project_designs tablosunu da kontrol et
            $existingDesign = $this->project->design->design_data;
        }

        return [
            'objeler' => $objeler,
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'existing_design' => $existingDesign,
            'project' => $this->project, // Proje bilgisini view'e gönder
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

        // JavaScript fonksiyonunu çağır
        $this->js('
            if (typeof window.collectAndSaveDesignData === "function") {
                window.collectAndSaveDesignData();
            } else {
                console.error("collectAndSaveDesignData fonksiyonu bulunamadı");
                alert("Tasarım kaydetme fonksiyonu bulunamadı. Lütfen sayfayı yenileyin.");
            }
        ');
    }

    public function storeDesignData($designData)
    {
        try {
            \Log::info('Design data received:', ['project_id' => $this->project->id, 'data' => $designData]);
            
            // Veri doğrulama
            if (!is_array($designData) || !isset($designData['elements'])) {
                throw new \Exception('Geçersiz tasarım verisi formatı');
            }

            // Tasarım verilerini kaydet veya güncelle
            // Projeye hem project_designs tablosuna hem de projects.design_landscape alanına kaydet
            ProjectDesign::updateOrCreate(
                ['project_id' => $this->project->id],
                ['design_data' => $designData]
            );

            // Projects tablosuna doğrudan JSON alanı olarak kaydet (yeni alan)
            $this->project->update(['design_landscape' => $designData]);
            
            // Projeyi tamamlanmış olarak işaretle
            $this->project->update(['design_completed' => true]);

            \Log::info('Design saved successfully', ['project_id' => $this->project->id, 'elements_count' => count($designData['elements'] ?? [])]);

            // Başarı mesajı
            \Filament\Notifications\Notification::make()
                ->title('Başarılı!')
                ->body('Tasarım başarıyla kaydedildi ve proje tamamlandı. (' . count($designData['elements'] ?? []) . ' element)')
                ->success()
                ->send();

            // Hemen yönlendir (Filament URL helper kullanarak)
            $this->js("
                setTimeout(function() {
                    window.location.href = '" . route('filament.admin.resources.projects.index') . "';
                }, 1500);
            ");

        } catch (\Exception $e) {
            \Log::error('Design save error:', ['project_id' => $this->project->id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            \Filament\Notifications\Notification::make()
                ->title('Hata!')
                ->body('Tasarım kaydedilirken bir hata oluştu: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getHeading(): string
    {
        if ($this->project) {
            return 'Peyzaj Tasarım Aracı - ' . $this->project->title;
        }
        return 'Peyzaj Tasarım Aracı';
    }

    public function getSubheading(): ?string
    {
        if ($this->project) {
            return 'Proje: ' . $this->project->title . ' | ' . $this->project->district . ', ' . $this->project->neighborhood . ' | Bütçe: ₺' . number_format($this->project->budget);
        }
        return 'Projeniz için peyzaj tasarımı oluşturun. Elementleri sürükleyip bırakarak tasarımınızı yapabilirsiniz.';
    }
}
