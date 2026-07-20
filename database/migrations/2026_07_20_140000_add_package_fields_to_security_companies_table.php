<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('security_companies', function (Blueprint $table) {
            $table->unsignedSmallInteger('package_size')->default(10)->after('is_active');
            $table->string('package_modality', 20)->default('manual')->after('package_size');
            $table->string('package_sku', 40)->nullable()->after('package_modality');
            $table->decimal('package_price_monthly', 12, 2)->nullable()->after('package_sku');
            $table->unsignedSmallInteger('max_clients')->default(10)->after('package_price_monthly');
        });

        DB::table('security_companies')->update([
            'package_size' => 50,
            'package_modality' => 'manual',
            'package_sku' => 'pack_50_manual',
            'package_price_monthly' => 1_800_000,
            'max_clients' => 50,
        ]);
    }

    public function down(): void
    {
        Schema::table('security_companies', function (Blueprint $table) {
            $table->dropColumn([
                'package_size',
                'package_modality',
                'package_sku',
                'package_price_monthly',
                'max_clients',
            ]);
        });
    }
};
