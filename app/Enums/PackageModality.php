<?php

declare(strict_types=1);

namespace App\Enums;

enum PackageModality: string
{
    case Manual = 'manual';
    case Hardware = 'hardware';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Sin hardware',
            self::Hardware => 'Con hardware',
        };
    }

    /** @return list<string> */
    public function features(): array
    {
        return config('tenancy.features.'.$this->value, []);
    }

    public function allows(string $feature): bool
    {
        return in_array($feature, $this->features(), true);
    }
}
