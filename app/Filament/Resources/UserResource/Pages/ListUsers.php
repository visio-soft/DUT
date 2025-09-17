<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('app.new_user'))
                ->icon('heroicon-o-plus'),
                
            Actions\Action::make('create_multiple')
                ->label(__('app.bulk_create_users'))
                ->icon('heroicon-o-users')
                ->color('success')
                ->form([
                    Forms\Components\Section::make(__('filament.sections.bulk_user_settings'))
                        ->description(__('filament.sections.bulk_user_settings_description'))
                        ->schema([
                            Forms\Components\TextInput::make('user_count')
                                ->label(__('filament.resources.user.fields.user_count'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(100)
                                ->default(1)
                                ->helperText(__('filament.helper_texts.user_count_range')),
                                
                            Forms\Components\TextInput::make('email_domain')
                                ->label(__('filament.resources.user.fields.email_domain'))
                                ->required()
                                ->default('basaksehir.bel.tr')
                                ->placeholder(__('filament.placeholders.email_domain_example'))
                                ->helperText(__('filament.helper_texts.email_format')),
                                
                            Forms\Components\TextInput::make('password')
                                ->label(__('filament.resources.user.fields.common_password'))
                                ->password()
                                ->required()
                                ->helperText(__('filament.helper_texts.common_password')),
                                
                            Forms\Components\Select::make('roles')
                                ->label(__('filament.resources.user.fields.roles'))
                                ->multiple()
                                ->options(Role::all()->pluck('name', 'id'))
                                ->preload()
                                ->searchable()
                                ->helperText(__('filament.helper_texts.assign_roles_all')),
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
                            $name = "Kullanıcı {$i}";
                            
                            // E-posta benzersizliği kontrolü
                            if (User::where('email', $email)->exists()) {
                                $errors[] = "{$email} - Bu e-posta adresi zaten kullanılıyor";
                                continue;
                            }
                            
                            $user = User::create([
                                'name' => $name,
                                'email' => $email,
                                'password' => Hash::make($password),
                                'email_verified_at' => now(),
                            ]);
                            
                            // Roller atanması
                            if (!empty($roles)) {
                                $roleModels = Role::whereIn('id', $roles)->get();
                                $user->assignRole($roleModels);
                            }
                            
                            $createdCount++;
                        } catch (\Exception $e) {
                            $errors[] = "{$i}@{$emailDomain} - Hata: " . $e->getMessage();
                        }
                    }
                    
                    if ($createdCount > 0) {
                        \Filament\Notifications\Notification::make()
                            ->title("{$createdCount} kullanıcı başarıyla oluşturuldu")
                            ->body("E-posta formatı: X@{$emailDomain} (X = 1'den {$userCount}'e kadar)")
                            ->success()
                            ->persistent()
                            ->send();
                    }
                    
                    if (!empty($errors)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Bazı kullanıcılar oluşturulamadı')
                            ->body(implode("\n", $errors))
                            ->warning()
                            ->persistent()
                            ->send();
                    }
                })
                ->modalHeading('Toplu Kullanıcı Oluştur')
                ->modalDescription('Belirtilen sayıda kullanıcıyı X@[domain] formatında oluşturur. (X = 1, 2, 3, ...)')
                ->modalSubmitActionLabel('Kullanıcıları Oluştur')
                ->modalWidth('2xl'),
        ];
    }
}
