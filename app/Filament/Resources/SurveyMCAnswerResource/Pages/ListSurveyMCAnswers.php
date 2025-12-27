<?php

namespace App\Filament\Resources\SurveyMCAnswerResource\Pages;

use App\Filament\Resources\SurveyMCAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyMCAnswers extends ListRecords
{
    protected static string $resource = SurveyMCAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewAnalytics')
                ->label(__('common.view_analytics'))
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(route('filament.admin.pages.dashboard')),
        ];
    }
}
