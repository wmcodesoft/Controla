<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Enums\ArchiveReason;
use App\Enums\CompanyAlertBucket;
use App\Enums\SubscriptionStatus;
use App\Models\SecurityCompany;
use Carbon\CarbonImmutable;

final class CompanySubscriptionState
{
    private const DUE_SOON_DAYS = 30;

    public static function bucket(SecurityCompany $company, ?CarbonImmutable $now = null): CompanyAlertBucket
    {
        $now ??= CarbonImmutable::now();

        if ($company->archived_at !== null || $company->archive_reason !== null) {
            return CompanyAlertBucket::Archived;
        }

        if ($company->subscription_status === SubscriptionStatus::Suspended) {
            return CompanyAlertBucket::Archived;
        }

        if ($company->subscription_status === SubscriptionStatus::Expired) {
            return CompanyAlertBucket::Overdue;
        }

        if ($company->subscription_status === SubscriptionStatus::Grace) {
            $graceEnds = $company->grace_ends_at ?? $company->package_ends_at?->addMonth();

            return ($graceEnds !== null && $graceEnds->isPast())
                ? CompanyAlertBucket::Overdue
                : CompanyAlertBucket::Overdue;
        }

        $endsAt = $company->package_ends_at;

        if ($endsAt === null) {
            return CompanyAlertBucket::Current;
        }

        if ($endsAt->isPast()) {
            return CompanyAlertBucket::Overdue;
        }

        $daysLeft = (int) $now->startOfDay()->diffInDays($endsAt->startOfDay(), false);

        if ($daysLeft <= self::DUE_SOON_DAYS) {
            return CompanyAlertBucket::DueSoon;
        }

        return CompanyAlertBucket::Current;
    }

    public static function daysUntilRenewal(SecurityCompany $company, ?CarbonImmutable $now = null): ?int
    {
        $now ??= CarbonImmutable::now();
        $endsAt = $company->package_ends_at;

        if ($endsAt === null) {
            return null;
        }

        return (int) $now->startOfDay()->diffInDays($endsAt->startOfDay(), false);
    }

    public static function matchesArchiveFilter(SecurityCompany $company, ?ArchiveReason $reason): bool
    {
        if ($reason === null) {
            return self::bucket($company) === CompanyAlertBucket::Archived;
        }

        return $company->archive_reason === $reason
            || ($reason === ArchiveReason::Recovery && $company->subscription_status === SubscriptionStatus::Suspended);
    }
}
