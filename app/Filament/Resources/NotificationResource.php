<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationLabel(): string
    {
        return __('common.notifications');
    }

    public static function getModelLabel(): string
    {
        return __('common.notification');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.notifications');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('common.user_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('common.notification_details'))
                    ->schema([
                        Forms\Components\Select::make('recipients')
                            ->label(__('common.recipients'))
                            ->options([
                                'all' => __('common.all_users'),
                                'specific' => __('common.specific_users'),
                            ])
                            ->default('specific')
                            ->live()
                            ->required()
                            ->dehydrated(false), // Don't save this to DB directly

                        Forms\Components\Select::make('users')
                            ->label(__('common.users'))
                            ->multiple()
                            ->options(User::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => $get('recipients') === 'specific')
                            ->required(fn (Forms\Get $get) => $get('recipients') === 'specific')
                            ->dehydrated(false), // Don't save this to DB directly

                        Forms\Components\TextInput::make('title')
                            ->label(__('common.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('body')
                            ->label(__('common.message'))
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('add_action_button')
                            ->label(__('common.add_action_button'))
                            ->live()
                            ->columnSpanFull()
                            ->dehydrated(false),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('action_label')
                                    ->label(__('common.button_label'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('action_url')
                                    ->label(__('common.button_url'))
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('add_action_button'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('notifiable.name')
                    ->label(__('common.recipient'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data.title')
                    ->label(__('common.title'))
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('data.body')
                    ->label(__('common.message'))
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.sent_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('read_at')
                    ->label(__('common.read_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder(__('common.unread'))
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
        ];
    }
}
