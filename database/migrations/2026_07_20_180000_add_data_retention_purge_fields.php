<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->timestamp('tenant_data_purged_at')->nullable()->after('archived_at');
        });

        Schema::table('security_companies', function (Blueprint $table) {
            $table->timestamp('commercial_anonymized_at')->nullable()->after('archive_reason');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('tenant_data_purged_at');
        });

        Schema::table('security_companies', function (Blueprint $table) {
            $table->dropColumn('commercial_anonymized_at');
        });
    }
};
