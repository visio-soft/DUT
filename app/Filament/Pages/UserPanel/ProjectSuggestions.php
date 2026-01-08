<?php

namespace App\Filament\Pages\UserPanel;

use App\Helpers\BackgroundImageHelper;
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
            'suggestions' => function ($query) {
                $query->with([
                    'likes',
                    'comments',
                    'createdBy',
                ]);
            },
        ])->findOrFail($id);

        $projectsQuery = Project::query()
            ->with([
                'suggestions' => function ($query) {
                    $query->with([
                        'likes',
                        'createdBy',
                    ]);
                },
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
        
        // Voting Status Filter (open/closed)
        // Matches Project::isExpired() logic: end_date->isPast()
        if ($votingStatus = $request->input('voting_status')) {
            if ($votingStatus === 'open') {
                // Voting is open if end_date is NULL or in the future
                $projectsQuery->where(function ($query) {
                    $query->whereNull('end_date')
                          ->orWhere('end_date', '>', now());
                });
            } elseif ($votingStatus === 'closed') {
                // Voting is closed if end_date is in the past
                $projectsQuery->whereNotNull('end_date')
                              ->where('end_date', '<=', now());
            }
        }
        
        // Survey Filter (has/doesn't have)
        if ($hasSurvey = $request->input('has_survey')) {
            if ($hasSurvey === 'yes') {
                $projectsQuery->whereHas('surveys');
            } elseif ($hasSurvey === 'no') {
                $projectsQuery->whereDoesntHave('surveys');
            }
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

        $filterValues = $request->only([
            'search',
            'country',
            'city',
            'district',
            'neighborhood',
            'start_date',
            'end_date',
            'min_budget',
            'max_budget',
            'has_survey',
            'voting_status',
        ]);

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.project-suggestions', array_merge(
            compact('project', 'projects', 'filterValues'),
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
