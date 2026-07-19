<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guard_logs', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('description');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->dateTime('signed_at')->nullable()->after('longitude');
            $table->boolean('is_panic')->default(false)->after('signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('guard_logs', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'signed_at', 'is_panic']);
        });
    }
};
