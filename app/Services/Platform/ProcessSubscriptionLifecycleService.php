<?php

declare(strict_types=1);

namespace App\Services\Platform;

use App\Enums\ArchiveReason;
use App\Enums\SubscriptionStatus;
use App\Models\SecurityCompany;
use Carbon\CarbonImmutable;

final class ProcessSubscriptionLifecycleService
{
    public function execute(?CarbonImmutable $now = null): int
    {
        $now ??= CarbonImmutable::now();
        $processed = 0;

        SecurityCompany::query()
            ->whereNull('archived_at')
            ->whereNotNull('package_ends_at')
            ->chunkById(50, function ($companies) use ($now, &$processed) {
                foreach ($companies as $company) {
                    if ($this->processCompany($company, $now)) {
                        $processed++;
                    }
                }
            });

        return $processed;
    }

    private function processCompany(SecurityCompany $company, CarbonImmutable $now): bool
    {
        $endsAt = $company->package_ends_at;

        if ($endsAt === null) {
            return false;
        }

        if ($company->subscription_status === SubscriptionStatus::Active && $endsAt->isPast()) {
            $company->update([
                'subscription_status' => SubscriptionStatus::Grace,
                'grace_ends_at' => $endsAt->addMonth(),
            ]);

            return true;
        }

        if ($company->subscription_status === SubscriptionStatus::Grace) {
            $graceEnds = $company->grace_ends_at ?? $endsAt->addMonth();

            if ($graceEnds->isPast()) {
                app(ArchiveCompanyService::class)->execute($company, ArchiveReason::Recovery);

                return true;
            }
        }

        if ($company->subscription_status === SubscriptionStatus::Expired && $endsAt->isPast()) {
            app(ArchiveCompanyService::class)->execute($company, ArchiveReason::Recovery);

            return true;
        }

        return false;
    }
}
