<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DragDroptest extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.pages.drag_droptest';

    protected static ?string $navigationLabel = 'Peyzaj Tasarım';

    protected static ?string $title = 'Peyzaj Tasarım Stüdyosu';

    // Bu sayfayı navigasyonda gizle (sadece modal içinde açılacak)
    protected static bool $shouldRegisterNavigation = false;

    // Sayfa slug'ını belirle
    protected static ?string $slug = 'drag-droptest';
}
