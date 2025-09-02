<?php

namespace App\Filament\Pages;

use App\Models\ProjectDesign;
use App\Models\Category;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TopDesignsGallery extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'En İyi Tasarımlar';
    protected static string $view = 'filament.pages.top-designs-gallery';
    protected static ?string $title = 'En İyi Tasarımlar';
    protected static ?int $navigationSort = 2;

    public $topDesigns = [];

    public function mount()
    {
        $this->loadTopDesigns();
    }

    protected function loadTopDesigns()
    {
        // Check if table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('project_designs')) {
            $this->topDesigns = [];
            return;
        }

        // Get all categories
        $categories = Category::with(['oneriler.design.likes'])
            ->whereHas('oneriler.design')
            ->get();

        $this->topDesigns = [];

        foreach ($categories as $category) {
            // Get all designs for this category, ordered by likes count
            $designs = ProjectDesign::with(['project', 'likes'])
                ->whereHas('project', function ($q) use ($category) {
                    $q->where('category_id', $category->id);
                })
                ->withCount('likes')
                ->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc') // Secondary sort for ties
                ->take(3) // Only top 3
                ->get();

            if ($designs->count() > 0) {
                $designsArray = $designs->map(function ($design, $index) {
                    $oneri = $design->project;

                    // Get oneri image
                    $oneriImage = '';
                    if ($oneri && $oneri->hasMedia('images')) {
                        $oneriImage = $oneri->getFirstMediaUrl('images');
                    }

                    // Build address
                    $projectAddress = '';
                    if ($oneri) {
                        $addressParts = [];
                        if (!empty($oneri->neighborhood)) {
                            $addressParts[] = $oneri->neighborhood;
                        }
                        if (!empty($oneri->district)) {
                            $addressParts[] = $oneri->district;
                        }
                        if (!empty($oneri->city)) {
                            $addressParts[] = $oneri->city;
                        }
                        $projectAddress = implode(', ', $addressParts);
                    }

                    // User name
                    $userName = $oneri->title ?? 'Kullanıcı';

                    // Determine rank (1st, 2nd, 3rd)
                    $rank = $index + 1;
                    $rankText = '';
                    $rankClass = '';
                    switch ($rank) {
                        case 1:
                            $rankText = '🏆 KAZANAN';
                            $rankClass = 'winner';
                            break;
                        case 2:
                            $rankText = '🥈 İKİNCİ';
                            $rankClass = 'second';
                            break;
                        case 3:
                            $rankText = '🥉 ÜÇÜNCÜ';
                            $rankClass = 'third';
                            break;
                    }

                    return [
                        'id' => $design->id,
                        'rank' => $rank,
                        'rank_text' => $rankText,
                        'rank_class' => $rankClass,
                        'project_name' => $oneri->title ?? 'Bilinmeyen Öneri',
                        'project_budget' => $oneri->budget ?? 0,
                        'project_image' => $oneriImage,
                        'project_address' => $projectAddress,
                        'user_name' => $userName,
                        'likes_count' => $design->likes_count,
                        'created_at' => $design->created_at->timestamp,
                    ];
                })->toArray();

                $this->topDesigns[] = [
                    'category_name' => $category->name,
                    'category_id' => $category->id,
                    'designs' => $designsArray,
                ];
            }
        }

        // Also handle designs without category
        $uncategorizedDesigns = ProjectDesign::with(['project', 'likes'])
            ->whereHas('project', function ($q) {
                $q->whereNull('category_id');
            })
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        if ($uncategorizedDesigns->count() > 0) {
            $designsArray = $uncategorizedDesigns->map(function ($design, $index) {
                $oneri = $design->project;

                // Get oneri image
                $oneriImage = '';
                if ($oneri && $oneri->hasMedia('images')) {
                    $oneriImage = $oneri->getFirstMediaUrl('images');
                }

                // Build address
                $projectAddress = '';
                if ($oneri) {
                    $addressParts = [];
                    if (!empty($oneri->neighborhood)) {
                        $addressParts[] = $oneri->neighborhood;
                    }
                    if (!empty($oneri->district)) {
                        $addressParts[] = $oneri->district;
                    }
                    if (!empty($oneri->city)) {
                        $addressParts[] = $oneri->city;
                    }
                    $projectAddress = implode(', ', $addressParts);
                }

                // User name
                $userName = $oneri->title ?? 'Kullanıcı';

                // Determine rank
                $rank = $index + 1;
                $rankText = '';
                $rankClass = '';
                switch ($rank) {
                    case 1:
                        $rankText = '🏆 KAZANAN';
                        $rankClass = 'winner';
                        break;
                    case 2:
                        $rankText = '🥈 İKİNCİ';
                        $rankClass = 'second';
                        break;
                    case 3:
                        $rankText = '🥉 ÜÇÜNCÜ';
                        $rankClass = 'third';
                        break;
                }

                return [
                    'id' => $design->id,
                    'rank' => $rank,
                    'rank_text' => $rankText,
                    'rank_class' => $rankClass,
                    'project_name' => $oneri->title ?? 'Bilinmeyen Öneri',
                    'project_budget' => $oneri->budget ?? 0,
                    'project_image' => $oneriImage,
                    'project_address' => $projectAddress,
                    'user_name' => $userName,
                    'likes_count' => $design->likes_count,
                    'created_at' => $design->created_at->timestamp,
                ];
            })->toArray();

            $this->topDesigns[] = [
                'category_name' => 'Kategori Yok',
                'category_id' => null,
                'designs' => $designsArray,
            ];
        }
    }

    public function getTitle(): string
    {
        return 'En İyi Tasarımlar';
    }

    public function getSubheading(): ?string
    {
        return 'Her kategoride en çok beğeni alan ilk 3 tasarım';
    }
}
