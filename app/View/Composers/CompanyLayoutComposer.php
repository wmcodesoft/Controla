<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Repositories\ClientRepository;
use Illuminate\View\View;

final class CompanyLayoutComposer
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
    ) {}

    public function compose(View $view): void
    {
        $user = auth()->user();

        $companyContext = [
            'company_name' => null,
            'is_quota_full' => true,
        ];

        if ($user !== null && $user->security_company_id && ! $user->hasRole('super-admin')) {
            $metrics = $this->clientRepository->metricsForCompany((int) $user->security_company_id);
            $companyContext = [
                'company_name' => $metrics['company_name'],
                'is_quota_full' => (bool) $metrics['is_quota_full'],
            ];
        }

        $view->with('companyContext', $companyContext);
    }
}
