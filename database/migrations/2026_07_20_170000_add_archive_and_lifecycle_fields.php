<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('security_companies', function (Blueprint $table) {
            $table->timestamp('grace_ends_at')->nullable()->after('package_ends_at');
            $table->timestamp('suspended_at')->nullable()->after('grace_ends_at');
            $table->timestamp('archived_at')->nullable()->after('suspended_at');
            $table->string('archive_reason', 20)->nullable()->after('archived_at');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('lifecycle', 30)->default('active')->after('is_active');
            $table->timestamp('released_at')->nullable()->after('lifecycle');
            $table->timestamp('archived_at')->nullable()->after('released_at');
        });
    }

    public function down(): void
    {
        Schema::table('security_companies', function (Blueprint $table) {
            $table->dropColumn(['grace_ends_at', 'suspended_at', 'archived_at', 'archive_reason']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['lifecycle', 'released_at', 'archived_at']);
        });
    }
};
