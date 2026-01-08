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
            self::MOST_VOTED => __('common.decision_most_voted'),
            self::ADMIN_CHOICE => __('common.decision_municipality_choice'),
            self::HYBRID => __('common.decision_hybrid'),
        };
    }
}
