<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectDesignResource\Pages;
use App\Filament\Resources\ProjectDesignResource\RelationManagers;
use App\Models\ProjectDesign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectDesignResource extends Resource
{
    protected static ?string $model = ProjectDesign::class;
    protected static ?string $pluralModelLabel = 'Proje Tasarımları';
    protected static ?string $modelLabel = 'Proje Tasarımı';
    protected static ?string $navigationLabel = 'Proje Tasarımları';
    protected static ?string $navigationGroup = 'Öneri Yönetimi';

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static bool $shouldRegisterNavigation = true; // Navigation'da gösterilsin

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Proje Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proje')
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),
                    ]),

                Forms\Components\Section::make('Tasarım Verileri')
                    ->schema([
                        Forms\Components\Textarea::make('design_data_display')
                            ->label('Tasarım Verisi (JSON)')
                            ->rows(10)
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->design_data) {
                                    return json_encode($record->design_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                }
                                return '';
                            }),

                        Forms\Components\KeyValue::make('design_summary')
                            ->label('Tasarım Özeti')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->design_data) {
                                    return [
                                        'Element Sayısı' => $record->design_data['total_elements'] ?? 0,
                                        'Oluşturulma Tarihi' => $record->design_data['timestamp'] ?? 'Bilinmiyor',
                                        'Proje ID' => $record->design_data['project_id'] ?? 'Bilinmiyor',
                                    ];
                                }
                                return [];
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('project.image')
                    ->label('Proje Resmi')
                    ->getStateUsing(function ($record) {
                        if ($record->project && $record->project->hasMedia('images')) {
                            return $record->project->getFirstMediaUrl('images');
                        }
                        return null;
                    })
                    ->defaultImageUrl(url('/images/no-image.png'))
                    ->size(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('project.title')
                    ->label('Proje Başlığı')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('project.budget')
                    ->label('Bütçe')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('elements_count')
                    ->label('Element Sayısı')
                    ->getStateUsing(function ($record) {
                        return $record->design_data['total_elements'] ?? 0;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('design_timestamp')
                    ->label('Tasarım Tarihi')
                    ->getStateUsing(function ($record) {
                        $timestamp = $record->design_data['timestamp'] ?? null;
                        if ($timestamp) {
                            return \Carbon\Carbon::parse($timestamp)->format('d.m.Y H:i');
                        }
                        return 'Bilinmiyor';
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('project.design_completed')
                    ->label('Tamamlandı')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')
                    ->label('Proje')
                    ->relationship('project', 'title')
                    ->searchable(),

                Tables\Filters\Filter::make('completed_projects')
                    ->label('Tamamlanan Projeler')
                    ->query(fn (Builder $query): Builder => $query->whereHas('project', fn (Builder $query) => $query->where('design_completed', true))),

                Tables\Filters\Filter::make('pending_projects')
                    ->label('Bekleyen Projeler')
                    ->query(fn (Builder $query): Builder => $query->whereHas('project', fn (Builder $query) => $query->where('design_completed', false))),

                Tables\Filters\Filter::make('popular_designs')
                    ->label('Popüler Tasarımlar (3+ Beğeni)')
                    ->query(fn (Builder $query): Builder => $query->withCount('likes')->having('likes_count', '>=', 3)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Görüntüle')
                        ->color('Gray')
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\Action::make('view_design')
                        ->label('Tasarımı Düzenle')
                        ->icon('heroicon-o-paint-brush')
                        ->color('primary')
                        ->url(function ($record) {
                            $projectImage = '';
                            if ($record->project && $record->project->hasMedia('images')) {
                                $projectImage = $record->project->getFirstMediaUrl('images');
                            }

                            return url('/admin/drag-drop-test?' . http_build_query([
                                'project_id' => $record->project_id,
                                'image' => $projectImage
                            ]));
                        })
                        ->openUrlInNewTab(false),

                    Tables\Actions\EditAction::make()
                        ->label('Veritabanında İncele')
                        ->color('info')
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Sil')
                        ->icon('heroicon-o-trash'),
                ])
                ->label('Aksiyonlar')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectDesigns::route('/'),
            'create' => Pages\CreateProjectDesign::route('/create'),
            'view' => Pages\ViewProjectDesign::route('/{record}'),
            'edit' => Pages\EditProjectDesign::route('/{record}/edit'),
        ];
    }
}
