<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Spatie\Permission\Models\Permission;

class OrganizedPermissionsComponent extends Component
{
    protected string $view = 'filament.components.organized-permissions';

    public static function make(): static
    {
        return new static();
    }

    public function getPermissionGroups(): array
    {
        $permissions = Permission::all();
        $groups = [];

        // Define permission groups with their patterns and Turkish labels
        $groupPatterns = [
            'user_management' => [
                'label' => 'Kullanıcı Yönetimi',
                'icon' => 'heroicon-o-users',
                'patterns' => ['user'],
                'color' => 'blue'
            ],
            'role_management' => [
                'label' => 'Rol Yönetimi',
                'icon' => 'heroicon-o-shield-check',
                'patterns' => ['role'],
                'color' => 'green'
            ],
            'suggestion_management' => [
                'label' => 'Öneri Yönetimi',
                'icon' => 'heroicon-o-light-bulb',
                'patterns' => ['oneri'],
                'color' => 'yellow'
            ],
            'project_management' => [
                'label' => 'Proje Yönetimi',
                'icon' => 'heroicon-o-briefcase',
                'patterns' => ['project'],
                'color' => 'purple'
            ],
            'category_management' => [
                'label' => 'Kategori Yönetimi',
                'icon' => 'heroicon-o-tag',
                'patterns' => ['category'],
                'color' => 'orange'
            ],
            'content_management' => [
                'label' => 'İçerik Yönetimi',
                'icon' => 'heroicon-o-document-text',
                'patterns' => ['obje', 'media'],
                'color' => 'indigo'
            ],
            'system_management' => [
                'label' => 'Sistem Yönetimi',
                'icon' => 'heroicon-o-cog-6-tooth',
                'patterns' => ['widget', 'page', 'permission'],
                'color' => 'gray'
            ],
        ];

        // Group permissions
        foreach ($groupPatterns as $groupKey => $groupData) {
            $groupPermissions = [];

            foreach ($permissions as $permission) {
                foreach ($groupData['patterns'] as $pattern) {
                    if (str_contains($permission->name, $pattern)) {
                        $groupPermissions[] = $permission;
                        break;
                    }
                }
            }

            if (!empty($groupPermissions)) {
                $groups[$groupKey] = [
                    'label' => $groupData['label'],
                    'icon' => $groupData['icon'],
                    'color' => $groupData['color'],
                    'permissions' => collect($groupPermissions)->unique('name')->sortBy('name')->values()->all()
                ];
            }
        }

        // Add ungrouped permissions
        $groupedPermissionNames = collect($groups)->flatMap(fn($group) => collect($group['permissions'])->pluck('name'))->unique();
        $ungroupedPermissions = $permissions->whereNotIn('name', $groupedPermissionNames);

        if ($ungroupedPermissions->isNotEmpty()) {
            $groups['other'] = [
                'label' => 'Diğer İzinler',
                'icon' => 'heroicon-o-ellipsis-horizontal',
                'color' => 'gray',
                'permissions' => $ungroupedPermissions->sortBy('name')->values()->all()
            ];
        }

        return $groups;
    }

    public function getFormSchema(): array
    {
        $groups = $this->getPermissionGroups();
        $sections = [];

        foreach ($groups as $groupKey => $groupData) {
            $sections[] = Section::make($groupData['label'])
                ->description("Bu grup, {$groupData['label']} ile ilgili izinleri içerir")
                ->icon($groupData['icon'])
                ->schema([
                    CheckboxList::make($groupKey . '_permissions')
                        ->hiddenLabel()
                        ->options(
                            collect($groupData['permissions'])->mapWithKeys(function ($permission) {
                                return [$permission->name => $this->formatPermissionLabel($permission->name)];
                            })->toArray()
                        )
                        ->columns(2)
                        ->gridDirection('row')
                        ->bulkToggleable()
                ])
                ->collapsible()
                ->collapsed(true)
                ->compact();
        }

        return $sections;
    }

    protected function formatPermissionLabel(string $permissionName): string
    {
        // Convert permission names to more readable format
        $labels = [
            'view' => 'Görüntüle',
            'view_any' => 'Tümünü Görüntüle',
            'create' => 'Oluştur',
            'update' => 'Güncelle',
            'delete' => 'Sil',
            'delete_any' => 'Toplu Sil',
            'force_delete' => 'Kalıcı Sil',
            'force_delete_any' => 'Toplu Kalıcı Sil',
            'restore' => 'Geri Yükle',
            'restore_any' => 'Toplu Geri Yükle',
            'replicate' => 'Kopyala',
            'reorder' => 'Sırala',
        ];

        $parts = explode('_', $permissionName);
        $resource = array_pop($parts);
        $action = implode('_', $parts);

        $actionLabel = $labels[$action] ?? ucfirst($action);
        $resourceLabel = ucfirst(str_replace(['_', '::'], [' ', ' '], $resource));

        return $actionLabel . ' - ' . $resourceLabel;
    }
}
