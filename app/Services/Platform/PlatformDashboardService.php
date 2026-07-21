<?php

declare(strict_types=1);

namespace App\Services\Platform;

use App\Enums\ArchiveReason;
use App\Enums\ClientLifecycle;
use App\Enums\CompanyAlertBucket;
use App\Models\Client;
use App\Models\PricingSettings;
use App\Models\SecurityCompany;
use App\Support\Tenancy\CompanySubscriptionState;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

final class PlatformDashboardService
{
    /** @return array<string, mixed> */
    public function build(Request $request): array
    {
        $alert = CompanyAlertBucket::tryFrom((string) $request->query('alert', ''));
        $archiveType = ArchiveReason::tryFrom((string) $request->query('archive', ''));
        $selectedCompanyId = $request->filled('company') ? (int) $request->query('company') : null;
        $globalView = $request->query('view') === 'global';

        $companies = SecurityCompany::query()
            ->with(['clients' => fn ($q) => $q->orderBy('name')])
            ->withCount([
                'clients as operational_clients_count' => fn ($q) => $q->where('lifecycle', ClientLifecycle::Active),
            ])
            ->orderBy('trade_name')
            ->get();

        $alertCounts = $this->countAlerts($companies);

        $filteredCompanies = $this->filterCompanies($companies, $alert, $archiveType);

        $selectedCompany = $selectedCompanyId
            ? $companies->firstWhere('id', $selectedCompanyId)
            : null;

        if ($selectedCompany === null && $filteredCompanies->isNotEmpty() && ! $globalView) {
            $selectedCompany = $filteredCompanies->first();
            $selectedCompanyId = $selectedCompany->id;
        }

        $detailRows = $globalView
            ? $this->globalRows($filteredCompanies)
            : $this->companyClientRows($selectedCompany, $filteredCompanies);

        return [
            'pricing' => PricingSettings::current(),
            'companies' => $filteredCompanies,
            'allCompanies' => $companies,
            'alertCounts' => $alertCounts,
            'alert' => $alert?->value,
            'archiveType' => $archiveType?->value,
            'selectedCompanyId' => $selectedCompanyId,
            'selectedCompany' => $selectedCompany,
            'globalView' => $globalView,
            'detailRows' => $detailRows,
        ];
    }

    /**
     * @param  Collection<int, SecurityCompany>  $companies
     * @return array<string, int>
     */
    private function countAlerts(Collection $companies): array
    {
        $counts = [
            CompanyAlertBucket::Current->value => 0,
            CompanyAlertBucket::DueSoon->value => 0,
            CompanyAlertBucket::Overdue->value => 0,
            CompanyAlertBucket::Archived->value => 0,
        ];

        foreach ($companies as $company) {
            $counts[CompanySubscriptionState::bucket($company)->value]++;
        }

        return $counts;
    }

    /**
     * @param  Collection<int, SecurityCompany>  $companies
     * @return Collection<int, SecurityCompany>
     */
    private function filterCompanies(
        Collection $companies,
        ?CompanyAlertBucket $alert,
        ?ArchiveReason $archiveType,
    ): Collection {
        if ($alert === null) {
            return $companies;
        }

        return $companies->filter(function (SecurityCompany $company) use ($alert, $archiveType) {
            $bucket = CompanySubscriptionState::bucket($company);

            if ($bucket !== $alert) {
                return false;
            }

            if ($alert === CompanyAlertBucket::Archived && $archiveType !== null) {
                return CompanySubscriptionState::matchesArchiveFilter($company, $archiveType);
            }

            return true;
        })->values();
    }

    /**
     * @param  Collection<int, SecurityCompany>  $companies
     * @return list<array<string, mixed>>
     */
    private function globalRows(Collection $companies): array
    {
        $rows = [];

        foreach ($companies as $company) {
            foreach ($company->clients as $client) {
                $rows[] = [
                    'type' => 'client',
                    'company' => $company,
                    'client' => $client,
                ];
            }

            if ($company->clients->isEmpty()) {
                $rows[] = [
                    'type' => 'company',
                    'company' => $company,
                    'client' => null,
                ];
            }
        }

        return $rows;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function companyClientRows(?SecurityCompany $selectedCompany, Collection $filteredCompanies): array
    {
        if ($selectedCompany !== null) {
            return $selectedCompany->clients->map(fn (Client $client) => [
                'type' => 'client',
                'company' => $selectedCompany,
                'client' => $client,
            ])->all();
        }

        return $filteredCompanies->map(fn (SecurityCompany $company) => [
            'type' => 'company',
            'company' => $company,
            'client' => null,
        ])->all();
    }
}
