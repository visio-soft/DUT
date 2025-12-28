<?php

namespace App\Filament\Resources\SurveyTextAnswerResource\Pages;

use App\Filament\Resources\SurveyTextAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyTextAnswers extends ListRecords
{
    protected static string $resource = SurveyTextAnswerResource::class;

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
