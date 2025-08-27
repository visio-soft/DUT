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
                ->label('Yeni Tasarım Oluştur')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(url('/admin/projects'))
                ->openUrlInNewTab(false),
        ];
    }

    public function getTitle(): string
    {
        return 'Proje Tasarımları';
    }

    public function getSubheading(): ?string
    {
        return 'Tüm proje tasarımlarını görüntüleyin ve yönetin.';
    }
}
