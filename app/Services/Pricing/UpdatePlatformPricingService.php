<?php

declare(strict_types=1);

namespace App\Services\Pricing;

use App\Models\PricingSettings;
use App\Models\User;

final class UpdatePlatformPricingService
{
    public function execute(float $unitManual, float $unitHardware, ?User $actor = null): PricingSettings
    {
        $settings = PricingSettings::current();

        $settings->update([
            'unit_price_manual' => $unitManual,
            'unit_price_hardware' => $unitHardware,
            'updated_by' => $actor?->id,
        ]);

        return $settings->fresh();
    }
}
