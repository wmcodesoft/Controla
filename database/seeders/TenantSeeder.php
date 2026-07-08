<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ClientPlanTier;
use App\Models\AccessLog;
use App\Models\Building;
use App\Models\Client;
use App\Models\Correspondence;
use App\Models\GuardLog;
use App\Models\HousingUnit;
use App\Models\Location;
use App\Models\PreAuthorization;
use App\Models\Resident;
use App\Models\SecurityCompany;
use App\Models\Vehicle;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $company = SecurityCompany::query()->firstOrCreate(
            ['tax_id' => '900123456-1'],
            [
                'legal_name' => 'SJ Seguridad Privada S.A.S.',
                'trade_name' => 'SJ Seguridad / BigSky',
                'email' => 'contacto@sj-seguridad.test',
                'phone' => '+57 300 000 0000',
                'is_active' => true,
            ]
        );

        $palmas = Client::query()->firstOrCreate(
            ['security_company_id' => $company->id, 'slug' => 'palmas-del-ingenio'],
            [
                'name' => 'Palmas del Ingenio',
                'login_suffix' => 'palmasdelingenio',
                'plan_tier' => ClientPlanTier::Deluxe,
                'max_structures' => ClientPlanTier::Deluxe->maxStructures(),
                'access_url' => 'https://controla.test',
                'is_active' => true,
            ]
        );

        $torres = Client::query()->firstOrCreate(
            ['security_company_id' => $company->id, 'slug' => 'torres-loma'],
            [
                'name' => 'Torres de la Loma',
                'login_suffix' => 'torresloma',
                'plan_tier' => ClientPlanTier::Economic,
                'max_structures' => ClientPlanTier::Economic->maxStructures(),
                'access_url' => 'https://controla.test',
                'is_active' => true,
            ]
        );

        $this->backfillOperationalData($palmas->id);
    }

    private function backfillOperationalData(int $clientId): void
    {
        $tables = [
            Location::class,
            Building::class,
            HousingUnit::class,
            Resident::class,
            Visitor::class,
            Vehicle::class,
            AccessLog::class,
            PreAuthorization::class,
            Correspondence::class,
            GuardLog::class,
        ];

        foreach ($tables as $modelClass) {
            DB::table((new $modelClass)->getTable())
                ->whereNull('client_id')
                ->update(['client_id' => $clientId]);
        }
    }
}
