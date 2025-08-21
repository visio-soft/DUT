<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Wizard::make([
                            Forms\Components\Wizard\Step::make('Ana Kategori')
                                ->icon('heroicon-o-rectangle-group')
                                ->description('Lütfen ana kategoriyi seçin')
                                ->schema([
                                    Forms\Components\Select::make('parent_category_id')
                                        ->label('Ana Kategori')
                                        ->options(\App\Models\Category::whereNull('parent_id')->pluck('name', 'id'))
                                        ->required()
                                ]),
                    Forms\Components\Wizard\Step::make('Alt Kategori')
                        ->icon('heroicon-o-list-bullet')
                        ->description('Seçilen ana kategoriye ait alt kategoriyi seçin')
                        ->columns(1)
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->label('Alt Kategori')
                                ->options(function ($get) {
                                    $parentId = $get('parent_category_id');
                                    if (!$parentId) return [];
                                    return \App\Models\Category::where('parent_id', $parentId)->pluck('name', 'id');
                                })
                                ->required(),
                        ]),
                    Forms\Components\Wizard\Step::make('Başlık')
                        ->icon('heroicon-o-pencil-square')
                        ->description('Proje başlığını girin')
                        ->columns(1)
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Başlık')
                                ->required(),
                        ]),
                    Forms\Components\Wizard\Step::make('Açıklama')
                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                        ->description('Proje açıklamasını girin')
                        ->columns(1)
                        ->schema([
                            Forms\Components\Textarea::make('description')
                                ->label('Açıklama'),
                        ]),
                    Forms\Components\Wizard\Step::make('Konum')
                        ->icon('heroicon-o-map-pin')
                        ->description('Projenin konumunu belirtin')
                        ->columns(1)
                        ->schema([
                            Forms\Components\TextInput::make('location')
                                ->label('Konum'),
                        ]),
                    Forms\Components\Wizard\Step::make('Bütçe')
                        ->icon('heroicon-o-currency-dollar')
                        ->description('Proje için bütçe belirleyin')
                        ->columns(1)
                        ->schema([
                            Forms\Components\TextInput::make('budget')
                                ->label('Bütçe')
                                ->numeric()
                                ->prefix('₺'),
                        ]),
                    Forms\Components\Wizard\Step::make('Resim')
                        ->icon('heroicon-o-photo')
                        ->description('Proje için bir görsel yükleyin (isteğe bağlı)')
                        ->columns(1)
                        ->schema([
                            Forms\Components\FileUpload::make('image_path')
                                ->label('Resim')
                                ->image()
                                ->directory('projects')
                                ->maxSize(2048),
                        ]),
                ])
                    ->columnSpanFull()
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                Tables\Columns\TextColumn::make('title')->label('Başlık'),
                Tables\Columns\TextColumn::make('budget')->label('Bütçe'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label('Oluşturulma'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
