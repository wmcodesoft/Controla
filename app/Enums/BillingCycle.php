<?php

declare(strict_types=1);

namespace App\Enums;

enum BillingCycle: string
{
    case Monthly = 'monthly';
    case Annual = 'annual';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'Mensual',
            self::Annual => 'Anual',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $cycle) => [$cycle->value => $cycle->label()])
            ->all();
    }
}
