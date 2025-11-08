<?php

namespace App\Filament\Resources\AnaKategorilerResource\Pages;

use App\Filament\Resources\AnaKategorilerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewAnaKategoriler extends ViewRecord
{
    protected static string $resource = AnaKategorilerResource::class;

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
                Infolists\Components\Section::make('Ana Kategori Bilgileri')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('id')
                                    ->label('ID'),
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Ana Kategori Adı')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                            ]),
                        
                        Infolists\Components\TextEntry::make('description')
                            ->label('Açıklama')
                            ->placeholder('Açıklama yok')
                            ->columnSpanFull(),

                        Infolists\Components\IconEntry::make('aktif')
                            ->label('Durum')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])
                    ->columns(1),

                Infolists\Components\Section::make('Alt Kategoriler')
                    ->schema([
                        Infolists\Components\TextEntry::make('categories.name')
                            ->label('Alt Kategoriler')
                            ->listWithLineBreaks()
                            ->badge()
                            ->placeholder('Alt kategori bulunmuyor')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->categories->count() > 0),

                Infolists\Components\Section::make('İstatistikler')
                    ->schema([
                        Infolists\Components\TextEntry::make('categories_count')
                            ->label('Alt Kategori Sayısı')
                            ->state(fn ($record) => $record->categories->count())
                            ->badge()
                            ->color('success'),
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
