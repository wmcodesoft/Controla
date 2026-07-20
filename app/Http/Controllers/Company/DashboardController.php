<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Enums\BillingCycle;
use App\Enums\CompanyPackageSku;
use App\Enums\PackageModality;
use App\Http\Controllers\Controller;
use App\Models\SecurityCompany;
use App\Repositories\ClientRepository;
use App\Services\Pricing\PriceCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly PriceCalculator $priceCalculator,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->can('company.dashboard'), 403);

        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.dashboard');
        }

        $companyId = (int) $user->security_company_id;
        abort_unless($companyId > 0, 403, 'Usuario sin empresa de seguridad asignada.');

        $company = SecurityCompany::query()->findOrFail($companyId);
        $metrics = $this->clientRepository->metricsForCompany($companyId);
        $recentClients = $this->clientRepository->recentForCompany($companyId, 6);

        $modality = PackageModality::tryFrom((string) $metrics['package_modality'])
            ?? PackageModality::Manual;
        $currentSize = (int) $metrics['max_clients'];
        $upgradeSizes = collect(config('tenancy.package_sizes', [1, 5, 10, 50, 100]))
            ->map(static fn ($size) => (int) $size)
            ->filter(static fn (int $size) => $size > $currentSize)
            ->values()
            ->all();

        $upgradeQuotes = [];
        foreach ($upgradeSizes as $index => $size) {
            $monthly = $this->priceCalculator->quote($modality, $size, BillingCycle::Monthly);
            $annual = $this->priceCalculator->quote($modality, $size, BillingCycle::Annual);
            $upgradeQuotes[] = [
                'size' => $size,
                'label' => CompanyPackageSku::fromParts($size, $modality)->label(),
                'monthly' => $monthly,
                'annual' => $annual,
                'recommended' => $index === 0,
            ];
        }

        $annualForCurrent = $this->priceCalculator->quote($modality, max(1, $currentSize), BillingCycle::Annual);
        $monthlyForCurrent = $this->priceCalculator->quote($modality, max(1, $currentSize), BillingCycle::Monthly);

        return view('modules.company.dashboard', compact(
            'company',
            'metrics',
            'recentClients',
            'upgradeQuotes',
            'annualForCurrent',
            'monthlyForCurrent',
        ));
    }
}
