<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('openEndedAnswers')
                ->label(__('common.open_ended_answers'))
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('warning')
                ->url(\App\Filament\Resources\SurveyTextAnswerResource::getUrl('index')),
            Actions\Action::make('viewAnalytics')
                ->label(__('common.view_analytics'))
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(route('filament.admin.pages.dashboard')),
            Actions\CreateAction::make(),
        ];
    }
}
