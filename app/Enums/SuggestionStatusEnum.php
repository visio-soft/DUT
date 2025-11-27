<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SuggestionStatusEnum: string implements HasColor, HasLabel
{
    case OPEN = 'open';
    case CLOSED = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::OPEN => __('common.status_voting_open'),
            self::CLOSED => __('common.status_voting_closed'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::OPEN => 'success',
            self::CLOSED => 'danger',
        };
    }
}
