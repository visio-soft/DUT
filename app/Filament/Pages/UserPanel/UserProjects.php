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
            'surveys' => function($q) {
                $q->where('status', true)
                  ->with(['responses' => function($q) {
                      $q->where('user_id', auth()->id());
                  }]);
            },
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

        if ($categoryId = $request->input('category_id')) {
            $projectsQuery->whereHas('projectGroups.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        // Survey filter
        if ($hasSurvey = $request->input('has_survey')) {
            if ($hasSurvey === 'yes') {
                $projectsQuery->whereHas('surveys', function ($q) {
                    $q->where('status', true);
                });
            } elseif ($hasSurvey === 'no') {
                $projectsQuery->whereDoesntHave('surveys', function ($q) {
                    $q->where('status', true);
                });
            }
        }

        $projects = $projectsQuery
            ->orderByDesc('start_date')
            ->get();

        $statusOptions = collect(SuggestionStatusEnum::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
            ->toArray();
        
        $categories = \App\Models\Category::pluck('name', 'id');
        
        // Location Data
        $countries = \App\Models\Country::pluck('name', 'name');
        $selectedCountry = $request->input('country');
        $cities = $selectedCountry 
            ? \App\Models\City::whereHas('country', fn($q) => $q->where('name', $selectedCountry))->pluck('name', 'name') 
            : [];

        $filterValues = $request->only([
            'search',
            'status',
            'category_id',
            'country',
            'city',
            'has_survey',
            'start_date',
            'end_date',
            'min_budget',
            'max_budget',
        ]);
        $filterValues['status'] = $statusFilter;

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.user-projects', array_merge(
            compact('projects', 'statusOptions', 'categories', 'countries', 'cities', 'filterValues'),
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
