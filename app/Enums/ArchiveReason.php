<?php

declare(strict_types=1);

namespace App\Enums;

enum ArchiveReason: string
{
    case Cancelled = 'cancelled';
    case Recovery = 'recovery';

    public function label(): string
    {
        return match ($this) {
            self::Cancelled => 'Baja voluntaria',
            self::Recovery => 'Cartera por recuperar',
        };
    }
}
