<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProjectSuggestionsOverviewChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Genel Bakış';

    protected static ?string $navigationLabel = 'Genel Bakış';

    public function getWidgets(): array
    {
        return [
            ProjectSuggestionsOverviewChart::class,
        ];
    }
}
