<?php

namespace App\Filament\Resources\ProjectDesignResource\Pages;

use App\Filament\Resources\ProjectDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectDesigns extends ListRecords
{
    protected static string $resource = ProjectDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new_design')
                ->label(__('filament.resources.project_design.actions.new_design'))
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(url('/admin/oneris'))
                ->openUrlInNewTab(false),
        ];
    }

    public function getTitle(): string
    {
        return __('filament.resources.project_design.page_titles.list');
    }

    public function getSubheading(): ?string
    {
        return __('filament.resources.project_design.descriptions.list');
    }
}
