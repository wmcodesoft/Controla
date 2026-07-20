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
        Schema::create('pricing_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('unit_price_manual', 12, 2);
            $table->decimal('unit_price_hardware', 12, 2);
            $table->string('currency', 3)->default('COP');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::table('pricing_settings')->insert([
            'unit_price_manual' => config('tenancy.pricing.default_unit_manual', 80_000),
            'unit_price_hardware' => config('tenancy.pricing.default_unit_hardware', 150_000),
            'currency' => config('tenancy.pricing.currency', 'COP'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('security_companies', function (Blueprint $table) {
            $table->string('billing_cycle', 20)->default('monthly')->after('max_clients');
            $table->decimal('unit_price_snapshot', 12, 2)->nullable()->after('billing_cycle');
            $table->decimal('volume_discount_pct', 5, 4)->nullable()->after('unit_price_snapshot');
            $table->decimal('annual_discount_pct', 5, 4)->nullable()->after('volume_discount_pct');
            $table->decimal('package_price_annual', 12, 2)->nullable()->after('annual_discount_pct');
            $table->timestamp('package_starts_at')->nullable()->after('package_price_annual');
            $table->timestamp('package_ends_at')->nullable()->after('package_starts_at');
            $table->string('subscription_status', 20)->default('active')->after('package_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('security_companies', function (Blueprint $table) {
            $table->dropColumn([
                'billing_cycle',
                'unit_price_snapshot',
                'volume_discount_pct',
                'annual_discount_pct',
                'package_price_annual',
                'package_starts_at',
                'package_ends_at',
                'subscription_status',
            ]);
        });

        Schema::dropIfExists('pricing_settings');
    }
};
