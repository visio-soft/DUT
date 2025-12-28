<?php

namespace App\Filament\Helpers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class CommonFilters
{
    /**
     * Create a location filter for district and neighborhood
     */
    public static function locationFilter(): Filter
    {
        return Filter::make('location')
            ->label(__('common.location'))
            ->form([
                Forms\Components\Select::make('district')
                    ->label(__('common.district'))
                    ->options(function () {
                        $keys = array_keys(config('istanbul_neighborhoods', []));

                        return array_combine($keys, $keys);
                    })
                    ->searchable(),

                Forms\Components\Select::make('neighborhood')
                    ->label(__('common.neighborhood'))
                    ->options(function (callable $get) {
                        $district = $get('district');
                        $map = config('istanbul_neighborhoods', []);

                        return $map[$district] ?? [];
                    })
                    ->searchable(),
            ])
            ->query(function (Builder $query, array $data) {
                if (! empty($data['district'])) {
                    $query->where('district', $data['district']);
                }

                if (! empty($data['neighborhood'])) {
                    $query->where('neighborhood', $data['neighborhood']);
                }
            });
    }

    /**
     * Create a date range filter
     */
    public static function dateRangeFilter(): Filter
    {
        return Filter::make('date_range')
            ->label(__('common.date_range'))
            ->form([
                Forms\Components\DatePicker::make('start_date')
                    ->label(__('common.start_date'))
                    ->placeholder(__('common.select_start_date')),
                Forms\Components\DatePicker::make('end_date')
                    ->label(__('common.end_date'))
                    ->placeholder(__('common.select_end_date')),
            ])
            ->query(function (Builder $query, array $data) {
                if (! empty($data['start_date'])) {
                    $query->where('start_date', '>=', $data['start_date']);
                }

                if (! empty($data['end_date'])) {
                    $query->where('end_date', '<=', $data['end_date']);
                }
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if ($data['start_date'] ?? null) {
                    $indicators[] = 'Başlangıç: '.Carbon::parse($data['start_date'])->format('d.m.Y');
                }

                if ($data['end_date'] ?? null) {
                    $indicators[] = 'Bitiş: '.Carbon::parse($data['end_date'])->format('d.m.Y');
                }

                return $indicators;
            });
    }

    /**
     * Create a budget range filter
     */
    public static function budgetRangeFilter(): Filter
    {
        return Filter::make('budget_filter')
            ->label(__('common.budget'))
            ->form([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('min_budget')
                            ->label(__('common.min_budget'))
                            ->numeric()
                            ->placeholder(__('common.min_budget_example')),
                        Forms\Components\TextInput::make('max_budget')
                            ->label(__('common.max_budget'))
                            ->numeric()
                            ->placeholder(__('common.max_budget_example')),
                    ])
                    ->columns(2),
            ])
            ->query(function (Builder $query, array $data) {
                if (! empty($data['min_budget'])) {
                    $query->where('min_budget', '>=', $data['min_budget']);
                }

                if (! empty($data['max_budget'])) {
                    $query->where('max_budget', '<=', $data['max_budget']);
                }
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if ($data['min_budget'] ?? null) {
                    $indicators[] = 'Min: ₺'.number_format($data['min_budget'], 2);
                }

                if ($data['max_budget'] ?? null) {
                    $indicators[] = 'Max: ₺'.number_format($data['max_budget'], 2);
                }

                return $indicators;
            });
    }

    /**
     * Create a creator type filter (user assigned vs not assigned/anonymous)
     *
     * @param  string  $anonymousLabel  Translation key for anonymous/not assigned option
     */
    public static function creatorTypeFilter(string $anonymousLabel = 'common.not_assigned'): SelectFilter
    {
        return SelectFilter::make('creator_type')
            ->label(__('common.creator_type'))
            ->options([
                'with_user' => __('common.user_assigned'),
                'anonymous' => __($anonymousLabel),
            ])
            ->query(function (Builder $query, array $data) {
                $value = $data['value'] ?? null;
                if ($value === 'with_user') {
                    $query->whereNotNull('created_by_id');
                } elseif ($value === 'anonymous') {
                    $query->whereNull('created_by_id');
                }
            });
    }

    /**
     * Create a likes count filter for models with likes
     */
    public static function likesFilter(): Filter
    {
        return Filter::make('likes_filter')
            ->label(__('common.like_count'))
            ->form([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('min_likes')
                            ->label(__('common.min_likes'))
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('max_likes')
                            ->label(__('common.max_likes'))
                            ->numeric(),
                    ])
                    ->columns(2),
            ])
            ->query(function (Builder $query, array $data) {
                $query->withCount('likes');

                if (! empty($data['min_likes'])) {
                    $query->having('likes_count', '>=', $data['min_likes']);
                }

                if (! empty($data['max_likes'])) {
                    $query->having('likes_count', '<=', $data['max_likes']);
                }
            });
    }
}
