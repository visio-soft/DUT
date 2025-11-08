<?php

namespace App\Filament\Resources\KategorilerResource\Pages;

use App\Filament\Resources\KategorilerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewKategoriler extends ViewRecord
{
    protected static string $resource = KategorilerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Düzenle'),
            Actions\DeleteAction::make()
                ->label('Sil'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Kategori Bilgileri')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('id')
                                    ->label('ID'),
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Kategori Adı')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                            ]),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Açıklama')
                            ->placeholder('Açıklama yok')
                            ->columnSpanFull(),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('mainCategory.name')
                                    ->label('Ana Kategori')
                                    ->placeholder('Ana Kategori Yok')
                                    ->badge()
                                    ->color('primary'),

                                Infolists\Components\IconEntry::make('aktif')
                                    ->label('Durum')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ]),
                    ])
                    ->columns(1),

                Infolists\Components\Section::make('İstatistikler')
                    ->schema([
                        Infolists\Components\TextEntry::make('oneriler_count')
                            ->label('Öneri Sayısı')
                            ->state(fn ($record) => $record->oneriler->count())
                            ->badge()
                            ->color('info'),
                    ]),

                Infolists\Components\Section::make('Zaman Bilgileri')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Oluşturulma')
                                    ->dateTime('d.m.Y H:i'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Güncellenme')
                                    ->dateTime('d.m.Y H:i'),

                                Infolists\Components\TextEntry::make('deleted_at')
                                    ->label('Silinme')
                                    ->dateTime('d.m.Y H:i')
                                    ->placeholder('Silinmemiş')
                                    ->visible(fn ($record) => $record->deleted_at !== null),
                            ]),
                    ])
                    ->collapsed(),
            ]);
    }
}
