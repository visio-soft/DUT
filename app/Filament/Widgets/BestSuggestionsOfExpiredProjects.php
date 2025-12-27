<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Suggestion;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class BestSuggestionsOfExpiredProjects extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = null;
    
    // Store ranks for each project
    protected array $suggestionRanks = [];

    public function __construct()
    {
        static::$heading = __('common.best_suggestions_of_expired_projects');
    }
    
    public function mount(): void
    {
        $this->calculateRanks();
    }
    
    protected function calculateRanks(): void
    {
        $expiredProjects = Project::where('end_date', '<', now())->pluck('id');
        
        foreach ($expiredProjects as $projectId) {
            $suggestions = Suggestion::where('project_id', $projectId)
                ->withCount('likes')
                ->orderByDesc('likes_count')  // En yüksek ilk sırada
                ->limit(3)
                ->get();
            
            $rank = 1;
            foreach ($suggestions as $suggestion) {
                $this->suggestionRanks[$suggestion->id] = $rank;
                $rank++;
            }
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->groups([
                Tables\Grouping\Group::make('project.title')
                    ->label(__('common.project'))
                    ->titlePrefixedWithLabel(false)
                    ->collapsible()
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query
                        ->orderBy('project_id')
                        ->orderByDesc('likes_count'))  // Force descending order by likes within group
                    ->getDescriptionFromRecordUsing(function ($record) {
                        $project = $record->project;
                        if (!$project) return null;
                        
                        $endDate = $project->end_date?->format('d.m.Y') ?? '-';
                        $totalLikes = $project->suggestions()->withCount('likes')->get()->sum('likes_count');
                        
                        return __('common.end_date') . ": {$endDate} | " . __('common.total_likes') . ": {$totalLikes}";
                    }),
            ])
            ->defaultGroup('project.title')
            ->defaultSort('likes_count', 'desc')  // Default sort by likes descending
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->state(function ($record) {
                        return $this->suggestionRanks[$record->id] ?? 1;
                    })
                    ->formatStateUsing(function ($state) {
                        $colors = [
                            1 => ['bg' => '#fbbf24', 'text' => '#000'], // Gold
                            2 => ['bg' => '#9ca3af', 'text' => '#000'], // Silver
                            3 => ['bg' => '#d97706', 'text' => '#fff'], // Bronze
                        ];
                        $color = $colors[$state] ?? ['bg' => '#6b7280', 'text' => '#fff'];
                        
                        return new HtmlString(
                            '<span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: ' . $color['bg'] . '; color: ' . $color['text'] . '; font-weight: bold; font-size: 14px;">' . $state . '</span>'
                        );
                    })
                    ->alignCenter()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('common.title'))
                    ->weight('bold')
                    ->wrap()
                    ->description(fn (Suggestion $record) => Str::limit($record->description, 80))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('likes_count')
                    ->label(__('common.like_count'))
                    ->badge()
                    ->color(fn (int $state): string => match(true) {
                        $state >= 50 => 'success',
                        $state >= 20 => 'warning',
                        default => 'gray'
                    })
                    ->icon('heroicon-o-heart')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('comments_count')
                    ->label(__('common.comment_count'))
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->alignCenter(),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateHeading(__('common.no_expired_projects'))
            ->emptyStateDescription(__('common.no_expired_projects_desc'))
            ->emptyStateIcon('heroicon-o-document-text');
    }

    protected function getQuery(): Builder
    {
        // Get expired projects
        $expiredProjects = Project::where('end_date', '<', now())->pluck('id');
        
        if ($expiredProjects->isEmpty()) {
            return Suggestion::query()->whereRaw('1 = 0'); // Return empty query
        }

        // For each expired project, get top 3 suggestions by likes
        $topSuggestionIds = [];
        
        foreach ($expiredProjects as $projectId) {
            $topSuggestions = Suggestion::where('project_id', $projectId)
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->limit(3)
                ->pluck('id')
                ->toArray();
            
            $topSuggestionIds = array_merge($topSuggestionIds, $topSuggestions);
        }

        return Suggestion::query()
            ->whereIn('id', $topSuggestionIds)
            ->with('project')
            ->withCount(['likes', 'comments'])
            ->orderBy('project_id')
            ->orderByDesc('likes_count');
    }
}
