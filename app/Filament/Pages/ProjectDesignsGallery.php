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

    public function getTitle(): string
    {
        return __('filament.pages.design_gallery.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.pages.design_gallery.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return null; // No group - this will appear at the top
    }

    protected static ?int $navigationSort = -100; // Ensure it appears first

    public $projectDesigns = [];
    public $sortBy = 'newest';
    public $priceFilter = '';

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

        $query = ProjectDesign::with(['project', 'project.category', 'likes'])
            ->whereHas('project');

        // Apply price filter
        if (!empty($this->priceFilter)) {
            $query->whereHas('project', function ($q) {
                if ($this->priceFilter === '0-10000') {
                    $q->whereBetween('budget', [0, 10000]);
                } elseif ($this->priceFilter === '10000-25000') {
                    $q->whereBetween('budget', [10000, 25000]);
                } elseif ($this->priceFilter === '25000-50000') {
                    $q->whereBetween('budget', [25000, 50000]);
                } elseif ($this->priceFilter === '50000+') {
                    $q->where('budget', '>=', 50000);
                }
            });
        }

        $designs = $query->get();

        // Group designs by their project's category name (fallback to "Kategori Yok")
        $grouped = $designs->groupBy(function ($design) {
            return $design->project && $design->project->category
                ? $design->project->category->name
                : 'Kategori Yok';
        });

        // Transform grouped collection into an array suitable for the Blade view
        $this->projectDesigns = $grouped->map(function ($group, $categoryName) {
            $designsArray = $group->map(function ($design) {
                $oneri = $design->project;

                // Get oneri image
                $oneriImage = '';
                if ($oneri && $oneri->hasMedia('images')) {
                    $oneriImage = $oneri->getFirstMediaUrl('images');
                }

                // Adres bilgilerini oluştur (mahalle, ilçe, şehir)
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

                // Kullanıcı bilgisi (önerilerde title'ı kullan)
                $userName = $oneri->title ?? 'Kullanıcı';

                return [
                    'id' => $design->id,
                    'project_name' => $oneri->title ?? 'Bilinmeyen Öneri',
                    'project_budget' => $oneri->budget ?? 0,
                    'project_image' => $oneriImage,
                    'project_address' => $projectAddress,
                    'user_name' => $userName,
                    'likes_count' => $design->likes->count(),
                    'is_liked' => Auth::check() ? $design->isLikedByUser(Auth::id()) : false,
                    'created_at' => $design->created_at->timestamp,
                ];
            })->toArray();

            // Apply sorting within each category
            if ($this->sortBy === 'newest') {
                usort($designsArray, function ($a, $b) {
                    return $b['created_at'] <=> $a['created_at'];
                });
            } elseif ($this->sortBy === 'popular') {
                usort($designsArray, function ($a, $b) {
                    return $b['likes_count'] <=> $a['likes_count'];
                });
            } elseif ($this->sortBy === 'price_high') {
                usort($designsArray, function ($a, $b) {
                    return $b['project_budget'] <=> $a['project_budget'];
                });
            } elseif ($this->sortBy === 'price_low') {
                usort($designsArray, function ($a, $b) {
                    return $a['project_budget'] <=> $b['project_budget'];
                });
            }

            // En son eklenen tasarımı belirle (her kategori için ayrı ayrı)
            if (!empty($designsArray)) {
                $latestCreatedAt = max(array_column($designsArray, 'created_at'));
                foreach ($designsArray as &$design) {
                    $design['is_latest'] = ($design['created_at'] === $latestCreatedAt);
                }
            }

            // Derive category-level start/end from the category itself (if available)
            // Fallback to project dates if category dates are not set
            $category = $group->first()?->project?->category;

            $startDateTime = null;
            $endDateTime = null;

            if ($category && ($category->start_datetime || $category->end_datetime)) {
                // Use category times (they include time, not just date)
                $startDateTime = $category->start_datetime;
                $endDateTime = $category->end_datetime;
            } else {
                // Fallback to project dates (only dates, so add time)
                $startDate = $group->map(function ($d) {
                    return $d->project?->start_date;
                })->filter()->min();

                $endDate = $group->map(function ($d) {
                    return $d->project?->end_date;
                })->filter()->max();

                if ($startDate) {
                    $startDateTime = \Carbon\Carbon::parse($startDate)->setTime(8, 0, 0); // 08:00
                }
                if ($endDate) {
                    $endDateTime = \Carbon\Carbon::parse($endDate)->setTime(18, 0, 0); // 18:00
                }
            }

            $startIso = $startDateTime ? \Carbon\Carbon::parse($startDateTime, 'Europe/Istanbul')->toIso8601String() : null;
            $endIso = $endDateTime ? \Carbon\Carbon::parse($endDateTime, 'Europe/Istanbul')->toIso8601String() : null;

            return [
                'category_name' => $categoryName,
                'designs' => $designsArray,
                'start_datetime' => $startIso,
                'end_datetime' => $endIso,
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

    public function updatedSortBy()
    {
        $this->loadProjectDesigns();
    }

    public function updatedPriceFilter()
    {
        $this->loadProjectDesigns();
    }
}
