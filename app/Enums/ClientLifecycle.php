<?php

declare(strict_types=1);

namespace App\Enums;

enum ClientLifecycle: string
{
    case Active = 'active';
    case Released = 'released';
    case ArchivedCompany = 'archived_company';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Operativo',
            self::Released => 'Retirado',
            self::ArchivedCompany => 'Archivado (empresa)',
        };
    }

    public function consumesQuota(): bool
    {
        return $this === self::Active;
    }
}
