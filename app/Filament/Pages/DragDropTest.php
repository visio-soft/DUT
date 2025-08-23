<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Obje;

class DragDropTest extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.pages.drag-drop-test';

    protected static ?string $title = 'Landscape Designer';

    protected static ?string $navigationLabel = 'Peyzaj Tasarımcısı';

    protected static bool $shouldRegisterNavigation = false; // Bu sayfayı navigation'da gösterme

    public ?string $projectImage = null;

    public function mount(): void
    {
        // Sadece yetkili kullanıcılar erişebilsin
        if (!\Illuminate\Support\Facades\Auth::check()) {
            abort(403);
        }

        // URL'den image parametresini al
        $this->projectImage = request()->get('image');
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

        return [
            'objeler' => $objeler,
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
                ->label('Tasarımı Kaydet')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->color('success')
                ->action(function () {
                    $this->js('saveDesign()');
                }),
        ];
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
