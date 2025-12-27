<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\SuggestionLike;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProjectSuggestionsOverviewChart extends ChartWidget
{
    protected static ?string $heading = null;

    protected static ?string $description = null;

    protected static ?string $maxHeight = '400px';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array{
     *     datasets: array<int, array<string, mixed>>,
     *     labels: array<int, string>
     * }
     */
    protected function getData(): array
    {
        $filter = $this->filter;

        // Base query for projects
        $projectsQuery = Project::query()
            ->withCount('suggestions')
            ->with([
                'suggestions' => function ($query) {
                    $query->withCount(['likes', 'comments']);
                },
            ]);

        // If filter is set, get only that project
        if ($filter) {
            $projectsQuery->where('id', $filter);
        } else {
            $projectsQuery->orderByDesc('suggestions_count')->limit(8);
        }

        /** @var Collection<int, Project> $projects */
        $projects = $projectsQuery->get();

        if ($projects->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => __('common.no_data'),
                        'data' => [0],
                        'backgroundColor' => '#d1d5db',
                        'borderColor' => '#9ca3af',
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => [__('common.no_data')],
            ];
        }

        $labels = $projects
            ->map(fn (Project $project) => $project->title ?: __('common.project_number', ['number' => $project->id]))
            ->all();

        $suggestionsCounts = $projects
            ->pluck('suggestions_count')
            ->map(fn ($count) => (int) $count)
            ->all();

        // Get likes counts by gender for each project
        $projectIds = $projects->pluck('id')->all();
        
        // Get gender-based like counts
        $genderLikes = SuggestionLike::query()
            ->join('suggestions', 'suggestion_likes.suggestion_id', '=', 'suggestions.id')
            ->whereIn('suggestions.project_id', $projectIds)
            ->select(
                'suggestions.project_id',
                'suggestion_likes.gender',
                'suggestion_likes.is_anonymous',
                DB::raw('count(*) as total')
            )
            ->groupBy('suggestions.project_id', 'suggestion_likes.gender', 'suggestion_likes.is_anonymous')
            ->get();

        $maleLikes = [];
        $femaleLikes = [];
        $anonymousLikes = [];

        foreach ($projects as $project) {
            $projectLikes = $genderLikes->where('project_id', $project->id);
            
            // Anonymous votes (is_anonymous = true)
            $anonymousCount = $projectLikes->where('is_anonymous', true)->sum('total');
            
            // Male votes (is_anonymous = false and gender = erkek or male)
            $maleCount = $projectLikes
                ->where('is_anonymous', false)
                ->whereIn('gender', ['erkek', 'male'])
                ->sum('total');
            
            // Female votes (is_anonymous = false and gender = kadın or female)
            $femaleCount = $projectLikes
                ->where('is_anonymous', false)
                ->whereIn('gender', ['kadın', 'female'])
                ->sum('total');

            $maleLikes[] = (int) $maleCount;
            $femaleLikes[] = (int) $femaleCount;
            $anonymousLikes[] = (int) $anonymousCount;
        }

        $commentsCounts = $projects
            ->map(fn (Project $project) => (int) $project->suggestions->sum('comments_count'))
            ->all();

        return [
            'datasets' => [
                [
                    'label' => __('common.suggestion_count'),
                    'data' => $suggestionsCounts,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('common.male') . ' ' . __('common.like_count'),
                    'data' => $maleLikes,
                    'backgroundColor' => '#3b82f6', // Blue
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('common.female') . ' ' . __('common.like_count'),
                    'data' => $femaleLikes,
                    'backgroundColor' => '#ec4899', // Pink
                    'borderColor' => '#db2777',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('common.anonymous_vote'),
                    'data' => $anonymousLikes,
                    'backgroundColor' => '#9ca3af', // Gray
                    'borderColor' => '#6b7280',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('common.comment_count'),
                    'data' => $commentsCounts,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#047857',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getHeading(): string
    {
        return __('common.project_suggestion_chart_heading');
    }

    public function getDescription(): ?string
    {
        return __('common.project_suggestion_chart_description');
    }

    protected function getFilters(): ?array
    {
        $projects = Project::query()->pluck('title', 'id')->toArray();
        
        return ['' => __('common.all_projects')] + $projects;
    }
}
