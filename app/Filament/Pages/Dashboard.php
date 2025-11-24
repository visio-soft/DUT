<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProjectSuggestionsOverviewChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = null;

    protected static ?string $navigationLabel = null;

    public function getWidgets(): array
    {
        return [
            ProjectSuggestionsOverviewChart::class,
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('common.general_overview');
    }

    public function getTitle(): string | Htmlable
    {
        return __('common.general_overview');
    }
}
