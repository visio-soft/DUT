<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;

class ProjectSuggestionsOverviewChart extends ChartWidget
{
    protected static ?string $heading = null;

    protected static ?string $description = null;

    protected static ?string $maxHeight = '360px';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array{
     *     datasets: array<int, array<string, mixed>>,
     *     labels: array<int, string>
     * }
     */
    protected function getData(): array
    {
        /** @var Collection<int, Project> $projects */
        $projects = Project::query()
            ->withCount('suggestions')
            ->with([
                'suggestions' => function ($query) {
                    $query->withCount(['likes', 'comments']);
                },
            ])
            ->orderByDesc('suggestions_count')
            ->limit(8)
            ->get();

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

        $likesCounts = $projects
            ->map(fn (Project $project) => (int) $project->suggestions->sum('likes_count'))
            ->all();

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
                    'label' => __('common.like_count'),
                    'data' => $likesCounts,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#1d4ed8',
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
}
