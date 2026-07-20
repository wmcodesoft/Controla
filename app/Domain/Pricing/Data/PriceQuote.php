<?php

declare(strict_types=1);

namespace App\Domain\Pricing\Data;

use App\Enums\BillingCycle;
use App\Enums\PackageModality;

final readonly class PriceQuote
{
    public function __construct(
        public PackageModality $modality,
        public int $size,
        public BillingCycle $cycle,
        public float $unitPrice,
        public float $volumeDiscountPct,
        public float $annualDiscountPct,
        public float $priceMonthly,
        public float $priceAnnual,
        public float $effectiveUnitMonthly,
        public float $listMonthlyWithoutVolume,
        public float $annualIfPaidMonthly,
        public float $annualSavings,
        public string $currency,
    ) {}

    public function amountDue(): float
    {
        return $this->cycle === BillingCycle::Annual
            ? $this->priceAnnual
            : $this->priceMonthly;
    }

    public function volumeDiscountLabel(): string
    {
        return number_format($this->volumeDiscountPct * 100, 0).'%';
    }

    public function annualDiscountLabel(): string
    {
        return number_format($this->annualDiscountPct * 100, 0).'%';
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'modality' => $this->modality->value,
            'modality_label' => $this->modality->label(),
            'size' => $this->size,
            'cycle' => $this->cycle->value,
            'cycle_label' => $this->cycle->label(),
            'unit_price' => $this->unitPrice,
            'volume_discount_pct' => $this->volumeDiscountPct,
            'annual_discount_pct' => $this->annualDiscountPct,
            'price_monthly' => $this->priceMonthly,
            'price_annual' => $this->priceAnnual,
            'effective_unit_monthly' => $this->effectiveUnitMonthly,
            'list_monthly_without_volume' => $this->listMonthlyWithoutVolume,
            'annual_if_paid_monthly' => $this->annualIfPaidMonthly,
            'annual_savings' => $this->annualSavings,
            'amount_due' => $this->amountDue(),
            'currency' => $this->currency,
        ];
    }
}
