<?php

namespace App\Filament\Pages\UserPanel;

use App\Enums\SuggestionStatusEnum;
use App\Helpers\BackgroundImageHelper;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserProjects
{
    /**
     * Display all projects with filters
     */
    public function index(Request $request)
    {
        $request = request();
        $statusFilter = $request->string('status')->toString();
        if ($statusFilter && ! SuggestionStatusEnum::tryFrom($statusFilter)) {
            $statusFilter = null;
        }

        $projectsQuery = Project::query()->with([
            'suggestions' => function ($query) use ($statusFilter) {
                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                }

                $query->with([
                    'likes',
                    'createdBy',
                ]);
            },
            'projectGroups.category',
        ]);

        if ($search = Str::lower($request->string('search')->toString())) {
            $projectsQuery->where(function (Builder $query) use ($search, $statusFilter) {
                $likeTerm = "%{$search}%";
                $query->whereRaw('LOWER(title) like ?', [$likeTerm])
                    ->orWhereRaw('LOWER(description) like ?', [$likeTerm])
                    ->orWhereHas('suggestions', function (Builder $suggestionQuery) use ($likeTerm, $statusFilter) {
                        if ($statusFilter) {
                            $suggestionQuery->where('status', $statusFilter);
                        }

                        $suggestionQuery->where(function (Builder $inner) use ($likeTerm) {
                            $inner->whereRaw('LOWER(title) like ?', [$likeTerm])
                                ->orWhereHas('createdBy', function (Builder $creatorQuery) use ($likeTerm) {
                                    $creatorQuery->whereRaw('LOWER(name) like ?', [$likeTerm]);
                                });
                        });
                    });
            });
        }

        if ($statusFilter) {
            $projectsQuery->whereHas('suggestions', function (Builder $query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            });
        }

        if ($country = $request->input('country')) {
            $projectsQuery->where('country', $country);
        }

        if ($city = $request->input('city')) {
            $projectsQuery->where('city', $city);
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

        $statusOptions = collect(SuggestionStatusEnum::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
            ->toArray();

        $districts = array_keys(config('istanbul_neighborhoods', []));
        $filterValues = $request->only([
            'search',
            'status',
            'status',
            'country',
            'city',
            'district',
            'neighborhood',
            'start_date',
            'end_date',
            'min_budget',
            'max_budget',
        ]);
        $filterValues['status'] = $statusFilter;

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.user-projects', array_merge(
            compact('projects', 'statusOptions', 'districts', 'filterValues'),
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
