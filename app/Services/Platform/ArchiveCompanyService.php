<?php

declare(strict_types=1);

namespace App\Services\Platform;

use App\Enums\ArchiveReason;
use App\Enums\ClientLifecycle;
use App\Enums\SubscriptionStatus;
use App\Models\Client;
use App\Models\SecurityCompany;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class ArchiveCompanyService
{
    public function execute(SecurityCompany $company, ArchiveReason $reason): SecurityCompany
    {
        return DB::transaction(function () use ($company, $reason) {
            $now = CarbonImmutable::now();

            $company->update([
                'is_active' => false,
                'subscription_status' => SubscriptionStatus::Suspended,
                'suspended_at' => $now,
                'archived_at' => $now,
                'archive_reason' => $reason,
            ]);

            Client::query()
                ->where('security_company_id', $company->id)
                ->where('lifecycle', '!=', ClientLifecycle::ArchivedCompany->value)
                ->update([
                    'lifecycle' => ClientLifecycle::ArchivedCompany,
                    'archived_at' => $now,
                    'is_active' => false,
                ]);

            return $company->fresh();
        });
    }
}
