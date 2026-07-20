<?php

declare(strict_types=1);

namespace App\Services\Tenant;

use App\Enums\BillingCycle;
use App\Enums\CompanyPackageSku;
use App\Enums\SubscriptionStatus;
use App\Models\SecurityCompany;
use App\Services\Pricing\PriceCalculator;
use Carbon\CarbonImmutable;

final class AssignCompanyPackageService
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
    ) {}

    public function execute(
        SecurityCompany $company,
        CompanyPackageSku $sku,
        BillingCycle $cycle = BillingCycle::Monthly,
        ?CarbonImmutable $startsAt = null,
    ): SecurityCompany {
        $quote = $this->priceCalculator->quote($sku->modality(), $sku->size(), $cycle);
        $startsAt ??= CarbonImmutable::now();
        $endsAt = $cycle === BillingCycle::Annual
            ? $startsAt->addYear()
            : $startsAt->addMonth();

        $company->update([
            'package_sku' => $sku,
            'package_size' => $sku->size(),
            'package_modality' => $sku->modality(),
            'max_clients' => $sku->size(),
            'billing_cycle' => $cycle,
            'unit_price_snapshot' => $quote->unitPrice,
            'volume_discount_pct' => $quote->volumeDiscountPct,
            'annual_discount_pct' => $quote->annualDiscountPct,
            'package_price_monthly' => $quote->priceMonthly,
            'package_price_annual' => $quote->priceAnnual,
            'package_starts_at' => $startsAt,
            'package_ends_at' => $endsAt,
            'subscription_status' => SubscriptionStatus::Active,
        ]);

        return $company->fresh();
    }
}
