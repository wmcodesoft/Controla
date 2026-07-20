<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Grace = 'grace';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Activa',
            self::Grace => 'En gracia',
            self::Expired => 'Vencida',
        };
    }
}
