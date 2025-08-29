<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\Obje;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class ViewDesign extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static string $view = 'filament.pages.view-design';
    protected static bool $shouldRegisterNavigation = false;
    
    public ?Project $project = null;
    public ?string $projectImage = null;
    public ?int $projectId = null;
    public ?array $designData = null;

    public function mount(): void
    {
        // Sadece yetkili kullanıcılar erişebilsin
        if (!Auth::check()) {
            abort(403);
        }

        // URL'den parametreleri al
        $this->projectId = request()->get('project_id');
        
        if (!$this->projectId) {
            abort(400, 'Proje ID eksik');
        }

        // Projeyi yükle
        $this->project = Project::find($this->projectId);
        
        if (!$this->project) {
            abort(404, 'Proje bulunamadı');
        }
        
        if (!$this->project->design_completed) {
            abort(403, 'Bu projenin tasarımı henüz tamamlanmamış');
        }

        // Proje resmini al
        if ($this->project->hasMedia('images')) {
            $this->projectImage = $this->project->getFirstMediaUrl('images');
        }

        // Tasarım verilerini yükle
        if ($this->project->design) {
            $this->designData = $this->project->design->design_data;
        }
    }

    public function getViewData(): array
    {
        // Proje tasarımında kullanılan objeleri çıkar ve say
        $projectObjects = [];
        
        if ($this->designData && isset($this->designData['elements'])) {
            $objectCounts = [];
            
            // Tasarım elemanlarından obje ID'lerini ve sayılarını hesapla
            foreach ($this->designData['elements'] as $element) {
                $objeId = $element['obje_id'] ?? null;
                if ($objeId) {
                    $objectCounts[$objeId] = ($objectCounts[$objeId] ?? 0) + 1;
                }
            }
            
            // Her benzersiz obje için detayları çek
            foreach ($objectCounts as $objeId => $count) {
                $obje = Obje::find($objeId);
                if ($obje) {
                    $imageUrl = null;
                    if ($obje->hasMedia('images')) {
                        $imageUrl = $obje->getFirstMediaUrl('images');
                    }
                    
                    $projectObjects[] = [
                        'id' => $obje->id,
                        'name' => $obje->name, // Yeni İngilizce sütun adı
                        'image_url' => $imageUrl,
                        'count' => $count,
                    ];
                }
            }
        }

        return [
            'project_objects' => $projectObjects, // Sadece projede kullanılan objeler
            'project_id' => $this->projectId,
            'project_image' => $this->projectImage,
            'existing_design' => $this->designData,
            'project' => $this->project,
            'view_only' => true, // Sadece görüntüleme modu
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('goBack')
                ->label('Projeye Dön')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(route('filament.admin.resources.projects.edit', $this->project))
                ->openUrlInNewTab(false),
                
            Action::make('editDesign')
                ->label('Tasarımı Düzenle')
                ->icon('heroicon-o-paint-brush')
                ->color('warning')
                ->url("/admin/drag-drop-test?project_id={$this->project->id}&image=" . urlencode($this->projectImage))
                ->openUrlInNewTab(false),
        ];
    }

    public function getHeading(): string
    {
        return 'Tasarım Görüntüleme';
    }

    public function getSubheading(): ?string
    {
        return $this->project ? "Proje: {$this->project->title}" : 'Proje tasarımını görüntüleme';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin/projects' => 'Projeler',
            '/admin/projects/' . $this->project?->id . '/edit' => $this->project?->title ?? 'Proje',
            '' => 'Tasarım Görüntüleme',
        ];
    }
}