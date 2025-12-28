<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProjectDecisionEnum: string implements HasLabel
{
    case MOST_VOTED = 'most_voted';
    case ADMIN_CHOICE = 'admin_choice';
    case HYBRID = 'hybrid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MOST_VOTED => 'En Çok Oy Alan',
            self::ADMIN_CHOICE => 'Yönetim Seçimi',
            self::HYBRID => 'Karma/Yeni Öneri',
        };
    }
}
