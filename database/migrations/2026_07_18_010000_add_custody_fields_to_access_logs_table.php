<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->boolean('has_custody')->default(false)->after('notes');
            $table->text('custody_description')->nullable()->after('has_custody');
            $table->string('custody_receiver_name', 255)->nullable()->after('custody_description');
            $table->dateTime('custody_received_at')->nullable()->after('custody_receiver_name');
        });
    }

    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropColumn(['has_custody', 'custody_description', 'custody_receiver_name', 'custody_received_at']);
        });
    }
};
