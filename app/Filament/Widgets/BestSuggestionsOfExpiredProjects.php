<?php

namespace App\Filament\Widgets;

use App\Models\Suggestion;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class BestSuggestionsOfExpiredProjects extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = null;

    public function __construct()
    {
        static::$heading = __('common.best_suggestions_of_expired_projects');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Suggestion::query()
                    ->whereHas('project', function ($query) {
                        $query->where('end_date', '<', now());
                    })
                    ->withCount('likes')
                    ->orderByDesc('likes_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('common.title'))
                    ->weight('bold')
                    ->description(fn (Suggestion $record) => Str::limit($record->description, 100)),
                
                Tables\Columns\TextColumn::make('likes_count')
                    ->label(__('common.like_count'))
                    ->badge()
                    ->color(fn (int $state): string => $state > 50 ? 'success' : 'gray')
                    ->icon('heroicon-o-heart'),

                Tables\Columns\TextColumn::make('project.title')
                    ->label(__('common.project'))
                    ->badge()
                    ->color('info'),
            ])
            ->paginated(false);
    }
}
