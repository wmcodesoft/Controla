<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropIndex('access_logs_host_id_entry_time_index');
            $table->unsignedBigInteger('host_id')->nullable()->change();
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->index(['host_id', 'entry_time']);
        });
    }

    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropIndex('access_logs_host_id_entry_time_index');
            $table->unsignedBigInteger('host_id')->nullable(false)->change();
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->index(['host_id', 'entry_time']);
        });
    }
};
