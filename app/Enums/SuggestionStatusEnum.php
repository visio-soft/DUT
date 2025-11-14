<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SuggestionStatusEnum: string implements HasColor, HasLabel
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case IMPLEMENTED = 'implemented';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __('common.status_pending'),
            self::UNDER_REVIEW => __('common.status_under_review'),
            self::APPROVED => __('common.status_approved'),
            self::REJECTED => __('common.status_rejected'),
            self::IMPLEMENTED => __('common.status_implemented'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::UNDER_REVIEW => 'info',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::IMPLEMENTED => 'primary',
        };
    }
}
