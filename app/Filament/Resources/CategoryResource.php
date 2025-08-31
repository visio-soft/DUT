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
    protected static ?string $pluralModelLabel = 'Kategoriler';
    protected static ?string $modelLabel = 'Kategori';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Kategori Adı')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Kategori adını girin'),
                Forms\Components\TextInput::make('icon')->label('Ikon (İsteğe Bağlı)')
                    ->maxLength(255)
                    ->placeholder('Ör: heroicon-o-home')
                    ->helperText('Heroicon, FontAwesome veya başka ikon kütüphanelerinden ikon adı'),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label('Üst Kategori (İsteğe Bağlı)')
                    ->placeholder('Ana kategori için boş bırakın')
                    ->searchable()
                    ->preload(),
                Forms\Components\Toggle::make('is_main')
                    ->label('Ana Kategori olarak işaretle')
                    ->helperText('Bu kategori ana kategori olarak işaretlenecektir.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Üst Kategori')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Ana Kategori'),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ikon')
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\IconColumn::make('is_main')
                    ->label('Ana')
                    ->boolean()
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
                Tables\Filters\SelectFilter::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label('Üst Kategori')
                    ->placeholder('Tüm Kategoriler')
                    ->searchable()
                    ->preload(),
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
            ->with(['parent:id,name'])
            ->withCount('oneriler');
    }
}
