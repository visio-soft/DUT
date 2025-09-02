<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'Projeler';
    protected static ?string $modelLabel = 'Proje';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Proje Adı')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Proje adını girin'),

                Forms\Components\DateTimePicker::make('start_datetime')->label('Başlangıç Tarihi ve Saati')
                    ->seconds(false)
                    ->format('Y-m-d H:i')
                    ->displayFormat('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->placeholder('01.01.2025 08:00')
                    ->helperText('Bu projedeki işlerin başlangıç tarihi ve saati (24 saat formatında, örn: 22:00)'),

                Forms\Components\DateTimePicker::make('end_datetime')->label('Bitiş Tarihi ve Saati')
                    ->seconds(false)
                    ->format('Y-m-d H:i')
                    ->displayFormat('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->placeholder('31.12.2025 22:00')
                    ->helperText('Bu projedeki işlerin bitiş tarihi ve saati (24 saat formatında, örn: 22:00)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Proje Adı')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_datetime')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_datetime')
                    ->label('Bitiş')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('oneriler_count')
                    ->label('Öneri Sayısı')
                    ->counts('oneriler')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('oneriler');
    }
}
