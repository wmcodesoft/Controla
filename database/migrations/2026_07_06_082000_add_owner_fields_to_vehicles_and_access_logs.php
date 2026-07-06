<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vehicles: add user_id for owner vehicles
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('visitor_id')->constrained()->nullOnDelete();
        });

        // Access logs: make visitor_id nullable, add user_id and access_type
        Schema::table('access_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('visitor_id')->constrained()->nullOnDelete();
            $table->string('access_type', 20)->default('visitor')->after('location_id');
        });

        // Modify visitor_id to be nullable (drops and recreates the FK)
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropForeign(['visitor_id']);
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->foreignId('visitor_id')->nullable()->change()->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'access_type']);
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropForeign(['visitor_id']);
            $table->foreignId('visitor_id')->change()->constrained()->cascadeOnDelete();
        });
    }
};
