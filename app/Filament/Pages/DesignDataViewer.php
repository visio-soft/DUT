<?php

namespace App\Filament\Pages;

use App\Models\Project;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class DesignDataViewer extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    
    protected static ?string $navigationLabel = 'Tasarım Verileri';
    
    protected static ?string $title = 'Tasarım Verileri';
    
    protected static string $view = 'filament.pages.design-data-viewer';
    
    protected static ?string $navigationGroup = 'Projeler';
    
    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->whereNotNull('design_landscape'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Proje Başlığı')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('design_completed')
                    ->label('Tamamlandı')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning'),
                    
                Tables\Columns\TextColumn::make('design_elements_count')
                    ->label('Element Sayısı')
                    ->state(function (Project $record) {
                        if (empty($record->design_landscape)) {
                            return 0;
                        }
                        
                        if (is_array($record->design_landscape)) {
                            // Yeni format: elements anahtarı altında
                            if (isset($record->design_landscape['elements'])) {
                                return count($record->design_landscape['elements']);
                            }
                            // Eski format: objects anahtarı altında (geriye dönük uyumluluk)
                            elseif (isset($record->design_landscape['objects'])) {
                                return count($record->design_landscape['objects']);
                            }
                            // Çok eski format: direkt array
                            else {
                                return count($record->design_landscape);
                            }
                        }
                        
                        return 'N/A';
                    })
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('design_data_size')
                    ->label('Veri Boyutu')
                    ->state(function (Project $record) {
                        if (empty($record->design_landscape)) {
                            return '0 KB';
                        }
                        
                        $jsonSize = strlen(json_encode($record->design_landscape));
                        
                        if ($jsonSize < 1024) {
                            return $jsonSize . ' B';
                        } elseif ($jsonSize < 1024 * 1024) {
                            return round($jsonSize / 1024, 2) . ' KB';
                        } else {
                            return round($jsonSize / (1024 * 1024), 2) . ' MB';
                        }
                    })
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Oluşturan')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('design_completed')
                    ->label('Tasarım Durumu')
                    ->options([
                        1 => 'Tamamlandı',
                        0 => 'Devam Ediyor',
                    ]),
                    
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_design')
                    ->label('Tasarımı Görüntüle')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Project $record): string => "/admin/view-design?project_id={$record->id}")
                    ->openUrlInNewTab(false),
                    
                Tables\Actions\Action::make('edit_design')
                    ->label('Tasarımı Düzenle')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->url(function (Project $record): string {
                        $imageUrl = '';
                        if ($record->hasMedia('images')) {
                            $imageUrl = $record->getFirstMediaUrl('images');
                        }
                        return "/admin/drag-drop-test?project_id={$record->id}&image=" . urlencode($imageUrl);
                    })
                    ->openUrlInNewTab(false),
                    
                Tables\Actions\Action::make('view_json')
                    ->label('JSON Görüntüle')
                    ->icon('heroicon-o-code-bracket')
                    ->color('gray')
                    ->modalContent(function (Project $record) {
                        return view('filament.modals.design-json-viewer', [
                            'project' => $record,
                            'designData' => $record->design_landscape,
                        ]);
                    })
                    ->modalHeading(fn (Project $record): string => "Tasarım Verisi - {$record->title}")
                    ->modalWidth('7xl'),
                    
                Tables\Actions\Action::make('export_json')
                    ->label('JSON İndir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Project $record) {
                        $filename = "design_data_{$record->id}_{$record->title}.json";
                        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename);
                        
                        return response()
                            ->json($record->design_landscape, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export_all_json')
                    ->label('Tümünü JSON Olarak İndir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($records) {
                        $allData = [];
                        foreach ($records as $record) {
                            $allData[] = [
                                'project_id' => $record->id,
                                'project_title' => $record->title,
                                'category' => $record->category->name ?? 'N/A',
                                'design_completed' => $record->design_completed,
                                'design_data' => $record->design_landscape,
                                'updated_at' => $record->updated_at?->toISOString(),
                            ];
                        }
                        
                        $filename = "all_design_data_" . date('Y_m_d_H_i_s') . ".json";
                        
                        return response()
                            ->json($allData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                    }),
            ])
            ->defaultSort('updated_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
