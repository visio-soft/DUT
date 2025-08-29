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

    protected static ?string $title = 'Proje Tasarımları Galerisi';
    protected static ?string $navigationLabel = 'Tasarım Galerisi';
    protected static ?string $navigationGroup = 'Proje Yönetimi';
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
            ->get()
            ->map(function ($design) {
                $project = $design->project;

                // Get project image
                $projectImage = '';
                if ($project && $project->hasMedia('images')) {
                    $projectImage = $project->getFirstMediaUrl('images');
                }

                return [
                    'id' => $design->id,
                    'project_name' => $project->title ?? 'Bilinmeyen Proje',
                    'project_budget' => $project->budget ?? 0,
                    'project_image' => $projectImage,
                    'likes_count' => $design->likes->count(),
                    'is_liked' => Auth::check() ? $design->isLikedByUser(Auth::id()) : false,
                    'created_at' => $design->created_at->timestamp,
                ];
            });

        $this->projectDesigns = $designs->toArray();
    }

    public function toggleLike($designId)
    {
        if (!Auth::check()) {
            $this->addError('like', 'Beğenmek için giriş yapmalısınız.');
            return;
        }

        $projectDesign = ProjectDesign::find($designId);
        if (!$projectDesign) {
            $this->addError('like', 'Tasarım bulunamadı.');
            return;
        }

        $userId = Auth::id();
        $message = '';

        // Check if already liked
        $existingLike = ProjectDesignLike::where('user_id', $userId)
            ->where('project_design_id', $projectDesign->id)
            ->first();

        if ($existingLike) {
            // Unlike - user can remove their like
            $existingLike->delete();
            $message = 'Beğeni kaldırıldı.';
        } else {
            // Like - ensure only one like per user per design
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
