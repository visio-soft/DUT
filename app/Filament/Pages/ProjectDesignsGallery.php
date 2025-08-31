<?php

namespace App\Filament\Pages;

use App\Models\ProjectDesign;
use App\Models\ProjectDesignLike;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ProjectDesignsGallery extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static string $view = 'filament.pages.project-designs-gallery';

    protected static ?string $title = 'Öneri Tasarımları Galerisi';
    protected static ?string $navigationLabel = 'Tasarım Galerisi';
    protected static ?string $navigationGroup = 'Öneri Yönetimi';
    protected static ?int $navigationSort = 10;

    public $projectDesigns = [];

    public function mount(): void
    {
        $this->loadProjectDesigns();
    }

    public function loadProjectDesigns(): void
    {
        // If migrations haven't created the table yet, skip loading designs
        if (!\Illuminate\Support\Facades\Schema::hasTable('project_designs')) {
            $this->projectDesigns = [];
            return;
        }

        $designs = ProjectDesign::with(['project', 'project.category', 'likes'])
            ->whereHas('project')
            ->get();

        // Group designs by their project's category name (fallback to "Kategori Yok")
        $grouped = $designs->groupBy(function ($design) {
            return $design->project && $design->project->category
                ? $design->project->category->name
                : 'Kategori Yok';
        });

        // Transform grouped collection into an array suitable for the Blade view
        $this->projectDesigns = $grouped->map(function ($group, $categoryName) {
            return [
                'category_name' => $categoryName,
                'designs' => $group->map(function ($design) {
                    $oneri = $design->project;

                    // Get oneri image
                    $oneriImage = '';
                    if ($oneri && $oneri->hasMedia('images')) {
                        $oneriImage = $oneri->getFirstMediaUrl('images');
                    }

                    return [
                        'id' => $design->id,
                        'project_name' => $oneri->title ?? 'Bilinmeyen Öneri',
                        'project_budget' => $oneri->budget ?? 0,
                        'project_image' => $oneriImage,
                        'likes_count' => $design->likes->count(),
                        'is_liked' => Auth::check() ? $design->isLikedByUser(Auth::id()) : false,
                        'created_at' => $design->created_at->timestamp,
                    ];
                })->toArray(),
            ];
        })->values()->toArray();
    }

    public function toggleLike($designId)
    {
        if (!Auth::check()) {
            $this->addError('like', 'Beğenmek için giriş yapmalısınız.');
            return;
        }

        $projectDesign = ProjectDesign::with('project')->find($designId);
        if (!$projectDesign) {
            $this->addError('like', 'Tasarım bulunamadı.');
            return;
        }

        $userId = Auth::id();
        $message = '';

        // If already liked this design -> remove like (toggle off)
        $existingLike = ProjectDesignLike::where('user_id', $userId)
            ->where('project_design_id', $projectDesign->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Beğeni kaldırıldı.';
        } else {
            // Enforce one like per category: remove any existing like by this user for other designs in the same category
            $categoryId = $projectDesign->project?->category_id;

            // Find designs that belong to projects in same category. If category_id is null, match whereNull.
            $designIdsInCategory = ProjectDesign::whereHas('project', function ($q) use ($categoryId) {
                if (is_null($categoryId)) {
                    $q->whereNull('category_id');
                } else {
                    $q->where('category_id', $categoryId);
                }
            })->pluck('id');

            ProjectDesignLike::where('user_id', $userId)
                ->whereIn('project_design_id', $designIdsInCategory)
                ->delete();

            // Create like for the selected design
            ProjectDesignLike::firstOrCreate([
                'user_id' => $userId,
                'project_design_id' => $projectDesign->id,
            ]);

            $message = 'Beğenildi!';
        }

        // Show success message
        \Filament\Notifications\Notification::make()
            ->title($message)
            ->success()
            ->send();

        // Reload data
        $this->loadProjectDesigns();
    }
}
