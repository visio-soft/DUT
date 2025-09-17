<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Notifications\Notification;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_template_roles')
                ->label('Rol Şablonları Oluştur')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->action(function () {
                    $this->createRoleTemplates();
                })
                ->requiresConfirmation()
                ->modalHeading('Rol Şablonları Oluştur')
                ->modalDescription('Yaygın kullanılan rol şablonları oluşturulacak. Mevcut roller etkilenmeyecek.')
                ->modalSubmitActionLabel('Oluştur'),

            Actions\CreateAction::make()
                ->label('Yeni Rol')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function createRoleTemplates(): void
    {
        $templates = [
            [
                'name' => 'editor',
                'description' => 'İçerik düzenleyici - Önerileri ve projeleri yönetebilir',
                'permissions' => [
                    'view_oneri', 'view_any_oneri', 'create_oneri', 'update_oneri',
                    'view_project', 'view_any_project', 'create_project', 'update_project',
                    'view_category', 'view_any_category',
                ]
            ],
            [
                'name' => 'moderator',
                'description' => 'Moderatör - İçerikleri onaylayabilir ve sınırlı kullanıcı yönetimi yapabilir',
                'permissions' => [
                    'view_oneri', 'view_any_oneri', 'create_oneri', 'update_oneri', 'delete_oneri',
                    'view_project', 'view_any_project', 'create_project', 'update_project', 'delete_project',
                    'view_category', 'view_any_category', 'create_category', 'update_category',
                    'view_user', 'view_any_user',
                ]
            ],
            [
                'name' => 'viewer',
                'description' => 'Görüntüleyici - Sadece içerikleri görüntüleyebilir',
                'permissions' => [
                    'view_oneri', 'view_any_oneri',
                    'view_project', 'view_any_project',
                    'view_category', 'view_any_category',
                ]
            ],
            [
                'name' => 'content_manager',
                'description' => 'İçerik Yöneticisi - Tüm içerik türlerini tam yönetebilir',
                'permissions' => [
                    'view_oneri', 'view_any_oneri', 'create_oneri', 'update_oneri', 'delete_oneri',
                    'view_project', 'view_any_project', 'create_project', 'update_project', 'delete_project',
                    'view_category', 'view_any_category', 'create_category', 'update_category', 'delete_category',
                    'view_project::design', 'view_any_project::design', 'create_project::design', 'update_project::design',
                ]
            ],
        ];

        $createdCount = 0;

        foreach ($templates as $template) {
            // Check if role already exists
            if (Role::where('name', $template['name'])->exists()) {
                continue;
            }

            // Create role
            $role = Role::create([
                'name' => $template['name'],
                'description' => $template['description'],
                'guard_name' => 'web',
            ]);

            // Assign permissions
            $permissions = Permission::whereIn('name', $template['permissions'])->get();
            $role->syncPermissions($permissions);

            $createdCount++;
        }

        if ($createdCount > 0) {
            Notification::make()
                ->title("$createdCount rol şablonu başarıyla oluşturuldu")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Tüm rol şablonları zaten mevcut')
                ->warning()
                ->send();
        }
    }
}
