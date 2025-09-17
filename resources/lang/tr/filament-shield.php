<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Rol Adı',
    'column.guard_name' => 'Güvenlik Koruyucusu',
    'column.roles' => 'Roller',
    'column.permissions' => 'İzinler',
    'column.updated_at' => 'Son Güncelleme',
    'column.team' => 'Takım',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Rol Adı',
    'field.guard_name' => 'Güvenlik Koruyucusu',
    'field.permissions' => 'İzinler',
    'field.select_all.name' => 'Tümünü Seç',
    'field.select_all.message' => 'Bu rol için mevcut olan <span class="text-primary font-medium">Tüm İzinleri</span> etkinleştir/devre dışı bırak',
    'field.team' => 'Takım',
    'field.team.placeholder' => 'Takım seçin',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Güvenlik Yönetimi',
    'nav.role.label' => 'Roller',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Rol',
    'resource.label.roles' => 'Roller',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Sistem Varlıkları',
    'section.basic_info' => 'Temel Bilgiler',
    'resources' => 'Kaynaklar',
    'widgets' => 'Widget\'lar',
    'pages' => 'Sayfalar',
    'custom' => 'Özel İzinler',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Bu işlem için yetkiniz bulunmuyor',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Görüntüle',
        'view_any' => 'Tümünü Görüntüle',
        'create' => 'Oluştur',
        'update' => 'Güncelle',
        'delete' => 'Sil',
        'delete_any' => 'Toplu Sil',
        'force_delete' => 'Kalıcı Sil',
        'force_delete_any' => 'Toplu Kalıcı Sil',
        'restore' => 'Geri Yükle',
        'reorder' => 'Sırala',
        'restore_any' => 'Toplu Geri Yükle',
        'replicate' => 'Kopyala',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Creation Helpers
    |--------------------------------------------------------------------------
    */

    'helpers' => [
        'role_name' => 'Rolün sistem genelinde kullanılacak benzersiz adı (örn: editor, moderator)',
        'guard_name' => 'Bu rol hangi güvenlik sistemi ile çalışacak (varsayılan: web)',
        'permissions_help' => 'Bu role verilecek izinleri seçin. İzinler kaynak bazında gruplandırılmıştır.',
        'role_created' => 'Rol başarıyla oluşturuldu',
        'role_updated' => 'Rol başarıyla güncellendi',
        'role_deleted' => 'Rol başarıyla silindi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Groups
    |--------------------------------------------------------------------------
    */

    'permission_groups' => [
        'user_management' => 'Kullanıcı Yönetimi',
        'content_management' => 'İçerik Yönetimi',
        'system_management' => 'Sistem Yönetimi',
        'reporting' => 'Raporlama',
        'project_management' => 'Proje Yönetimi',
        'suggestion_management' => 'Öneri Yönetimi',
    ],
];
