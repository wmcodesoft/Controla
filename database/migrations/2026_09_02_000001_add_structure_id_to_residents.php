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
        Schema::table('residents', function (Blueprint $table) {
            $table->foreignId('structure_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
        });

        // Migrate data from resident_housing_unit pivot to residents.structure_id
        // Use the primary housing unit if available, otherwise the first one
        $pivots = DB::table('resident_housing_unit')
            ->select('resident_id', 'housing_unit_id', 'is_primary')
            ->orderBy('is_primary', 'desc')
            ->orderBy('id')
            ->get()
            ->keyBy('resident_id');

        // We need the housing_unit -> structure mapping from the main migration
        // The unitMap is built in the main migration, so we query structures that have legacy_housing_unit_id in metadata
        $structures = DB::table('structures')
            ->whereNotNull('metadata')
            ->get()
            ->mapWithKeys(fn ($s) => [
                data_get(json_decode($s->metadata, true), 'legacy_housing_unit_id') => $s->id,
            ]);

        foreach ($pivots as $residentId => $pivot) {
            $structureId = $structures[$pivot->housing_unit_id] ?? null;
            if ($structureId) {
                DB::table('residents')->where('id', $residentId)->update(['structure_id' => $structureId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropForeign(['structure_id']);
            $table->dropColumn('structure_id');
        });
    }
};
