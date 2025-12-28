<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatusEnum: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case VOTING_CLOSED = 'voting_closed';
    case ARCHIVED = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Taslak',
            self::ACTIVE => 'Yayında (Oylama Aktif)',
            self::COMPLETED => 'Sonuçlandı',
            self::VOTING_CLOSED => 'Oylama Kapandı (Karar Bekleniyor)',
            self::ARCHIVED => 'Arşiv',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::ACTIVE => 'green',
            self::COMPLETED => 'blue',
            self::VOTING_CLOSED => 'warning',
            self::ARCHIVED => 'red',
        };
    }
}
