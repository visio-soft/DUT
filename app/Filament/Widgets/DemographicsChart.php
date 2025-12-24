<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\SuggestionLike;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DemographicsChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('common.demographics_overview');
    }
    
    protected static ?string $maxHeight = '400px';
    
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $filter = $this->filter;
        
        $query = SuggestionLike::query()
            ->join('suggestions', 'suggestion_likes.suggestion_id', '=', 'suggestions.id');

        if ($filter) {
            $query->where('suggestions.project_id', $filter);
        }

        // Fetch aggregation: Sugestion Title, Gender, Is Anonymous
        $data = $query->select(
                'suggestions.title as suggestion_title',
                'suggestion_likes.gender',
                'suggestion_likes.is_anonymous',
                DB::raw('count(*) as total')
            )
            ->groupBy('suggestions.title', 'suggestion_likes.gender', 'suggestion_likes.is_anonymous')
            ->get();
            
        // Get unique titles sorted by total votes
        $suggestions = $data->groupBy('suggestion_title')
            ->map(fn ($group) => $group->sum('total'))
            ->sortDesc()
            ->take(10); // Take top 10
            
        $labels = $suggestions->keys()->all();
        
        $maleData = [];
        $femaleData = [];
        $anonymousData = [];
        
        foreach ($labels as $title) {
            $group = $data->where('suggestion_title', $title);
            
            // If Anonymous is true, count it towards Anonymous regardless of gender
            $anonymousCount = $group->where('is_anonymous', true)->sum('total');
            
            // If Anonymous is false, count towards specific gender
            $maleCount = $group->where('is_anonymous', false)->where('gender', 'male')->sum('total');
            $femaleCount = $group->where('is_anonymous', false)->where('gender', 'female')->sum('total');
            
            $maleData[] = $maleCount;
            $femaleData[] = $femaleCount;
            $anonymousData[] = $anonymousCount;
        }
        
        return [
            'datasets' => [
                [
                    'label' => __('common.male'),
                    'data' => $maleData,
                    'backgroundColor' => '#3b82f6', // Blue
                ],
                [
                    'label' => __('common.female'),
                    'data' => $femaleData,
                    'backgroundColor' => '#ec4899', // Pink
                ],
                [
                    'label' => __('common.anonymous_vote'),
                    'data' => $anonymousData,
                    'backgroundColor' => '#9ca3af', // Gray
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return Project::query()->pluck('title', 'id')->toArray();
    }
}
