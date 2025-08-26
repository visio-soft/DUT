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
        $this->projectImage = request()->get('image');
        $this->projectId = request()->get('project_id') ?: request()->get('project');
        
        if ($this->projectId) {
            $this->project = Project::find($this->projectId);
            
            if (!$this->project) {
                abort(404, 'Proje bulunamadı');
            }
            
            // Proje resmi URL'ini güncelle
            if ($this->project->hasMedia('images')) {
                $this->projectImage = $this->project->getFirstMediaUrl('images');
            }
            
            if ($this->project->design_completed) {
                // Design data'yı project.design_landscape alanından al
                $this->designData = $this->project->design_landscape;
                
                // Eğer orada yoksa project_designs tablosundan al
                if (!$this->designData && $this->project->design) {
                    $this->designData = $this->project->design->design_data;
                }
            }
        }
        
        if (!$this->project) {
            abort(404, 'Proje bulunamadı');
        }
        
        if (!$this->project->design_completed) {
            abort(403, 'Bu projenin tasarımı henüz tamamlanmamış');
        }
    }

    public function getViewData(): array
    {
        // Objeler tablosundan tüm objeleri çek (tasarım görüntüleme için gerekli)
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

    public function getTitle(): string|Htmlable
    {
        return $this->project ? "Tasarım Görüntüleme - {$this->project->title}" : 'Tasarım Görüntüleme';
    }
    
    public function getHeading(): string|Htmlable
    {
        if ($this->project) {
            return 'Tasarım Görüntüleme - ' . $this->project->title;
        }
        return 'Tasarım Görüntüleme';
    }

    public function getSubheading(): ?string
    {
        if ($this->project) {
            return 'Proje: ' . $this->project->title . ' | ' . $this->project->district . ', ' . $this->project->neighborhood . ' | Bütçe: ₺' . number_format($this->project->budget);
        }
        return 'Kaydedilen tasarımı görüntülüyorsunuz.';
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
