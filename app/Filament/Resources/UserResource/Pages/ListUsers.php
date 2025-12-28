<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('common.create_user'))
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->button(),

            Actions\Action::make('create_multiple')
                ->label(__('common.bulk_create_users'))
                ->icon('heroicon-o-users')
                ->color('success')
                ->form([
                    Forms\Components\Section::make(__('common.bulk_user_settings'))
                        ->description(__('common.bulk_user_settings_description'))
                        ->schema([
                            Forms\Components\TextInput::make('user_count')
                                ->label(__('common.user_count'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(100)
                                ->default(1)
                                ->helperText(__('common.user_count_helper')),

                            Forms\Components\TextInput::make('email_domain')
                                ->label(__('common.email_domain'))
                                ->required()
                                ->default('basaksehir.bel.tr')
                                ->placeholder(__('common.email_domain_placeholder'))
                                ->helperText(__('common.email_domain_helper')),

                            Forms\Components\TextInput::make('password')
                                ->label(__('common.shared_password'))
                                ->password()
                                ->required()
                                ->helperText(__('common.shared_password_helper')),

                            Forms\Components\Select::make('roles')
                                ->label(__('common.roles'))
                                ->multiple()
                                ->options(Role::all()->pluck('name', 'id'))
                                ->preload()
                                ->searchable()
                                ->helperText(__('common.roles_helper')),
                        ])
                        ->columns(2),
                ])
                ->action(function (array $data) {
                    $userCount = (int) $data['user_count'];
                    $password = $data['password'];
                    $emailDomain = $data['email_domain'];
                    $roles = $data['roles'] ?? [];

                    $createdCount = 0;
                    $errors = [];

                    for ($i = 1; $i <= $userCount; $i++) {
                        try {
                            $email = "{$i}@{$emailDomain}";
                            $name = __('common.bulk_user_name', ['number' => $i]);

                            // E-posta benzersizliği kontrolü
                            if (User::where('email', $email)->exists()) {
                                $errors[] = __('common.bulk_user_exists', ['email' => $email]);

                                continue;
                            }

                            $user = User::create([
                                'name' => $name,
                                'email' => $email,
                                'password' => Hash::make($password),
                                'email_verified_at' => now(),
                            ]);

                            // Roller atanması
                            if (! empty($roles)) {
                                $roleModels = Role::whereIn('id', $roles)->get();
                                $user->assignRole($roleModels);
                            }

                            $createdCount++;
                        } catch (\Exception $e) {
                            $errors[] = __('common.bulk_user_error', [
                                'email' => "{$i}@{$emailDomain}",
                                'message' => $e->getMessage(),
                            ]);
                        }
                    }

                    if ($createdCount > 0) {
                        \Filament\Notifications\Notification::make()
                            ->title(__('common.bulk_users_created', ['count' => $createdCount]))
                            ->body(__('common.bulk_users_created_body', [
                                'domain' => $emailDomain,
                                'total' => $userCount,
                            ]))
                            ->success()
                            ->persistent()
                            ->send();
                    }

                    if (! empty($errors)) {
                        \Filament\Notifications\Notification::make()
                            ->title(__('common.bulk_users_failed'))
                            ->body(implode("\n", $errors))
                            ->warning()
                            ->persistent()
                            ->send();
                    }
                })
                ->modalHeading(__('common.bulk_create_users'))
                ->modalDescription(__('common.bulk_create_description'))
                ->modalSubmitActionLabel(__('common.create_users'))
                ->modalWidth('2xl'),
        ];
    }
}
