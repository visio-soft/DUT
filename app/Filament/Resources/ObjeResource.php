<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjeResource\Pages;
use App\Filament\Resources\ObjeResource\RelationManagers;
use App\Models\Obje;
use App\Rules\ImageFormatRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ObjeResource extends Resource
{
    protected static ?string $model = Obje::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    
    protected static ?string $navigationLabel = 'Objeler';
    
    protected static ?string $modelLabel = 'Obje';
    
    protected static ?string $pluralModelLabel = 'Objeler';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Obje Bilgileri')
                    ->description('Objenizin bilgilerini girin')
                    ->schema([
                        Forms\Components\TextInput::make('isim')
                            ->label('İsim')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Obje ismini girin'),
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Resim Yükle')
                            ->collection('images')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->maxFiles(1)
                            ->directory('objeler')
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml'])
                            ->helperText('Önerilen: Arka planı kırpılmış (şeffaf) PNG formatında bir resim.')
                            ->hintColor('info')
                            ->preserveFilenames()
                            ->enableOpen()
                            ->enableDownload()
                            ->columnSpanFull()
                            ->visibility('public')
                            ->disk('public')
                            ->nullable()
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio(null)
                            ->imageResizeTargetWidth('2000')
                            ->imageResizeTargetHeight('2000'),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Resim')
                    ->collection('images')
                    ->height(60)
                    ->width(60)
                    ->square()
                    ->disk('public')
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('isim')
                    ->label('İsim')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturma Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncelleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('isim')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListObjes::route('/'),
            'create' => Pages\CreateObje::route('/create'),
            'edit' => Pages\EditObje::route('/{record}/edit'),
        ];
    }
}
