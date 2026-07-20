<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Client;
use App\Models\SecurityCompany;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class SecurityCompanyRepository
{
    public function platformMetrics(): array
    {
        return [
            'companies_total' => SecurityCompany::query()->count(),
            'companies_active' => SecurityCompany::query()->where('is_active', true)->count(),
            'clients_total' => Client::query()->count(),
            'clients_active' => Client::query()->where('is_active', true)->count(),
            'users_total' => User::query()->count(),
        ];
    }

    /** @return Collection<int, SecurityCompany> */
    public function recentCompanies(int $limit = 5): Collection
    {
        return SecurityCompany::query()
            ->withCount('clients')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return SecurityCompany::query()
            ->withCount('clients')
            ->orderBy('trade_name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): SecurityCompany
    {
        return SecurityCompany::query()
            ->withCount('clients')
            ->findOrFail($id);
    }
}
