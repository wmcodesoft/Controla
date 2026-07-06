<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('correspondence', function (Blueprint $table) {
            $table->foreignId('housing_unit_id')->nullable()->after('visitor_id')->constrained()->nullOnDelete();
            $table->foreignId('resident_id')->nullable()->after('housing_unit_id')->constrained()->nullOnDelete();
            $table->foreignId('host_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('correspondence', function (Blueprint $table) {
            $table->dropForeign(['housing_unit_id']);
            $table->dropForeign(['resident_id']);
            $table->dropColumn(['housing_unit_id', 'resident_id']);
        });
    }
};
