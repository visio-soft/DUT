<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesignRelationManager extends RelationManager
{
    protected static string $relationship = 'design';

    protected static ?string $title = 'Tasarım Görüntüleme';

    protected static ?string $label = 'Tasarım';

    protected static ?string $pluralLabel = 'Tasarım';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ViewField::make('design_preview')
                    ->label('Tasarım Önizleme')
                    ->view('custom.design-preview')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\ViewColumn::make('design_preview')
                    ->label('Tasarım Önizleme')
                    ->view('custom.design-table-preview')
                    ->searchable(false)
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Read-only, creation ve editing disable
            ])
            ->actions([
                Tables\Actions\Action::make('view_full_design')
                    ->label('Tam Ekran Görüntüle')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(function ($record) {
                        $project = $this->ownerRecord;
                        $imageUrl = $project->hasMedia('images') ? $project->getFirstMediaUrl('images') : '';
                        return "/admin/view-design?project_id={$project->id}&image=" . urlencode($imageUrl);
                    })
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                // Bulk actions disabled for read-only
            ]);
    }
}
