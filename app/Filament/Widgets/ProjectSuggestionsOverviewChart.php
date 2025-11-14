<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;

class ProjectSuggestionsOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Proje Öneri İstatistikleri';

    protected static ?string $description = 'Her proje için öneri, beğeni ve yorum dağılımını gösterir.';

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
                        'label' => 'Veri Yok',
                        'data' => [0],
                        'backgroundColor' => '#d1d5db',
                        'borderColor' => '#9ca3af',
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => ['Veri Yok'],
            ];
        }

        $labels = $projects
            ->map(fn (Project $project) => $project->title ?: "Proje #{$project->id}")
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
                    'label' => 'Öneri Sayısı',
                    'data' => $suggestionsCounts,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Beğeni Sayısı',
                    'data' => $likesCounts,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Yorum Sayısı',
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
}
