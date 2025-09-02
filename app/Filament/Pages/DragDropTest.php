<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Obje;
use App\Models\Oneri;
use App\Models\ProjectDesign;
use App\Models\Category;
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
    public ?Oneri $project = null;

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
            $this->project = Oneri::find($this->projectId);

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
        // Kategorileri çek
        $kategoriler = [];

        // Önce Obje modelindeki sabit kategorileri kullan
        foreach (Obje::CATEGORIES as $key => $name) {
            $kategoriler[] = [
                'id' => $key,
                'name' => $name,
                'type' => 'static' // Sabit kategori
            ];
        }

        // Category tablosundan da kategorileri çek (eğer varsa)
        $dbKategoriler = Category::all();
        foreach ($dbKategoriler as $kategori) {
            $kategoriler[] = [
                'id' => $kategori->id,
                'name' => $kategori->name,
                'type' => 'database', // Veritabanı kategorisi
                'icon' => $kategori->icon
            ];
        }

        // Objeler tablosundan tüm objeleri çek
        $objeler = Obje::all()->map(function ($obje) {
            return [
                'id' => $obje->id,
                'name' => $obje->name, // Yeni İngilizce sütun adı
                'category' => $obje->category, // Yeni İngilizce sütun adı
                'image_url' => $obje->hasMedia('images') ? $obje->getFirstMediaUrl('images') : null,
            ];
        });

        // Mevcut tasarımı yükle (eğer varsa)
        $existingDesign = $this->project && $this->project->design
            ? $this->project->design->design_data
            : null;

        return [
            'objeler' => $objeler,
            'kategoriler' => $kategoriler,
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'existing_design' => $existingDesign,
            'project' => $this->project,
        ];
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
            const elements = designElements.map(element => ({
                obje_id: element.obje_id,
                x: element.x,
                y: element.y,
                width: element.width,
                height: element.height,
                scale: element.scale
            }));

            const design = {
                project_id: ' . $this->project->id . ',
                elements: elements,
                timestamp: new Date().toISOString(),
                total_elements: elements.length
            };

            $wire.call("storeDesignData", design);
        ');
    }

    public function storeDesignData($designData)
    {
        try {
            $elementsCount = is_array($designData) && isset($designData['total_elements']) ? $designData['total_elements'] : 0;

            // Tasarım verilerini kaydet veya güncelle
            ProjectDesign::updateOrCreate(
                ['project_id' => $this->project->id],
                ['design_data' => $designData]
            );

            // Projeyi tamamlanmış olarak işaretle
            $this->project->update(['design_completed' => true]);

            // Başarı mesajı ve yönlendirme
            \Filament\Notifications\Notification::make()
                ->title('Başarılı!')
                ->body('Tasarım başarıyla kaydedildi ve proje tamamlandı.')
                ->success()
                ->send();

            $this->js("setTimeout(() => window.location.href = '/admin/oneris', 100);");

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
        return 'Tasarım Aracı';
    }

    public function getSubheading(): ?string
    {
        return 'Projeniz için tasarımı oluşturun. Elementleri sürükleyip bırakarak tasarımınızı yapabilirsiniz.';
    }
}
