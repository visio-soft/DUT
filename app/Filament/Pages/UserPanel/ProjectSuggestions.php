<?php

namespace App\Filament\Pages\UserPanel;

use App\Enums\ProjectStatusEnum;
use App\Helpers\BackgroundImageHelper;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProjectSuggestions
{
    /**
     * Display project suggestions page
     */
    public function show($id)
    {
        $request = request();
        $search = Str::lower($request->string('search')->toString());

        $project = Project::with([
            'suggestions.likes',
            'suggestions.comments',
            'suggestions.createdBy',
        ])->findOrFail($id);

        $projectsQuery = Project::query()
            ->with([
                'suggestions.likes',
                'suggestions.createdBy',
                'projectGroups.category',
            ]);

        if ($search) {
            $projectsQuery->where(function (Builder $query) use ($search) {
                $likeTerm = "%{$search}%";
                $query->whereRaw('LOWER(title) like ?', [$likeTerm])
                    ->orWhereRaw('LOWER(description) like ?', [$likeTerm])
                    ->orWhereHas('suggestions', function (Builder $suggestionQuery) use ($likeTerm) {
                        $suggestionQuery->where(function (Builder $inner) use ($likeTerm) {
                            $inner->whereRaw('LOWER(title) like ?', [$likeTerm])
                                ->orWhereHas('createdBy', function (Builder $creatorQuery) use ($likeTerm) {
                                    $creatorQuery->whereRaw('LOWER(name) like ?', [$likeTerm]);
                                });
                        });
                    });
            });
        }

        if ($status = $request->input('status')) {
            $projectsQuery->where('status', $status);
        }

        if ($categoryId = $request->input('category_id')) {
            $projectsQuery->whereHas('projectGroups', function (Builder $query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        if ($creatorType = $request->input('creator_type')) {
            if ($creatorType === 'with_user') {
                $projectsQuery->whereNotNull('created_by_id');
            } elseif ($creatorType === 'not_assigned') {
                $projectsQuery->whereNull('created_by_id');
            }
        }

        if ($district = $request->input('district')) {
            $projectsQuery->where('district', $district);
        }

        if ($neighborhood = $request->input('neighborhood')) {
            $projectsQuery->where('neighborhood', $neighborhood);
        }

        if ($startDate = $request->input('start_date')) {
            $projectsQuery->whereDate('start_date', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $projectsQuery->whereDate('end_date', '<=', $endDate);
        }

        if ($minBudget = $request->input('min_budget')) {
            $projectsQuery->where('min_budget', '>=', $minBudget);
        }

        if ($maxBudget = $request->input('max_budget')) {
            $projectsQuery->where('max_budget', '<=', $maxBudget);
        }

        $projects = $projectsQuery
            ->orderByDesc('start_date')
            ->get();

        if ($search) {
            $filteredSuggestions = $project->suggestions->filter(function ($suggestion) use ($search) {
                $titleMatches = Str::contains(Str::lower($suggestion->title), $search);
                $creatorMatches = $suggestion->createdBy
                    ? Str::contains(Str::lower($suggestion->createdBy->name), $search)
                    : false;

                return $titleMatches || $creatorMatches;
            })->values();
            $project->setRelation('suggestions', $filteredSuggestions);
        }

        $statusOptions = collect(ProjectStatusEnum::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
            ->toArray();

        $filterCategories = Category::orderBy('name')->get();
        $districts = array_keys(config('istanbul_neighborhoods', []));
        $filterValues = $request->only([
            'search',
            'status',
            'category_id',
            'creator_type',
            'district',
            'neighborhood',
            'start_date',
            'end_date',
            'min_budget',
            'max_budget',
        ]);

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.project-suggestions', array_merge(
            compact('project', 'projects', 'statusOptions', 'filterCategories', 'districts', 'filterValues'),
            $backgroundData
        ));
    }

    /**
     * Get background image data for views
     */
    private function getBackgroundImageData(): array
    {
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return compact('hasBackgroundImages', 'randomBackgroundImage');
    }
}
