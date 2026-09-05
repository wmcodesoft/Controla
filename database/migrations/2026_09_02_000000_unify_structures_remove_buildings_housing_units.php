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
        // ── 1. Add location_id to structures ──
        Schema::table('structures', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
        });

        // ── 2. Add structure_id to access_logs, correspondence, common_zone_bookings ──
        Schema::table('access_logs', function (Blueprint $table) {
            $table->foreignId('structure_id')->nullable()->after('housing_unit_id')->constrained()->nullOnDelete();
        });

        Schema::table('correspondence', function (Blueprint $table) {
            $table->foreignId('structure_id')->nullable()->after('housing_unit_id')->constrained()->nullOnDelete();
        });

        Schema::table('common_zone_bookings', function (Blueprint $table) {
            $table->foreignId('structure_id')->nullable()->after('housing_unit_id')->constrained()->nullOnDelete();
        });

        // ── 3. Migrate data: buildings → structures (root level, type=block) ──
        $buildings = DB::table('buildings')->whereNull('deleted_at')->get();
        $buildingMap = [];

        foreach ($buildings as $building) {
            // Find or create a root structure for this client
            $rootId = DB::table('structures')
                ->where('client_id', $building->client_id)
                ->whereNull('parent_id')
                ->value('id');

            if (!$rootId) {
                $rootId = DB::table('structures')->insertGetId([
                    'client_id' => $building->client_id,
                    'parent_id' => null,
                    'structure_type_id' => DB::table('structure_types')->where('code', 'general_area')->value('id') ?? 1,
                    'name' => DB::table('clients')->where('id', $building->client_id)->value('name') ?? 'Conjunto',
                    'code' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Determine structure_type_id based on building type
            $typeCode = match ($building->type) {
                'torre', 'bloque' => 'block',
                'casa' => 'house',
                default => 'block',
            };
            $structureTypeId = DB::table('structure_types')->where('code', $typeCode)->value('id') ?? 1;

            $blockId = DB::table('structures')->insertGetId([
                'client_id' => $building->client_id,
                'parent_id' => $rootId,
                'structure_type_id' => $structureTypeId,
                'name' => $building->name,
                'second_node_name' => null,
                'code' => $building->code,
                'location_id' => $building->location_id,
                'is_active' => $building->is_active,
                'created_at' => $building->created_at,
                'updated_at' => $building->updated_at,
            ]);

            $buildingMap[$building->id] = $blockId;
        }

        // ── 4. Migrate data: housing_units → structures (child level, type=apartment) ──
        $units = DB::table('housing_units')->whereNull('deleted_at')->get();
        $unitMap = [];

        foreach ($units as $unit) {
            $parentBlockId = $buildingMap[$unit->building_id] ?? null;
            if (!$parentBlockId) continue;

            $parentName = DB::table('structures')->where('id', $parentBlockId)->value('name') ?? '';

            $typeCode = match ($unit->type) {
                'apartamento', 'apto' => 'apartment',
                'casa' => 'house',
                'local', 'local_comercial' => 'commercial_store',
                'oficina' => 'office',
                'bodega' => 'warehouse',
                default => 'apartment',
            };
            $structureTypeId = DB::table('structure_types')->where('code', $typeCode)->value('id') ?? 1;

            $metadata = null;
            if ($unit->floor || $unit->notes) {
                $metadata = json_encode(array_filter([
                    'floor' => $unit->floor,
                    'notes' => $unit->notes,
                    'legacy_housing_unit_id' => $unit->id,
                ]));
            } else {
                $metadata = json_encode(['legacy_housing_unit_id' => $unit->id]);
            }

            $apartmentId = DB::table('structures')->insertGetId([
                'client_id' => $unit->client_id,
                'parent_id' => $parentBlockId,
                'structure_type_id' => $structureTypeId,
                'name' => $parentName,
                'second_node_name' => $unit->unit_number,
                'code' => $unit->unit_number,
                'metadata' => $metadata,
                'is_active' => $unit->is_active,
                'created_at' => $unit->created_at,
                'updated_at' => $unit->updated_at,
            ]);

            $unitMap[$unit->id] = $apartmentId;
        }

        // ── 5. Populate structure_id from housing_unit_id ──
        DB::table('access_logs')
            ->whereNotNull('housing_unit_id')
            ->orderBy('id')
            ->each(function ($log) use ($unitMap) {
                $structureId = $unitMap[$log->housing_unit_id] ?? null;
                if ($structureId) {
                    DB::table('access_logs')->where('id', $log->id)->update(['structure_id' => $structureId]);
                }
            });

        DB::table('correspondence')
            ->whereNotNull('housing_unit_id')
            ->orderBy('id')
            ->each(function ($row) use ($unitMap) {
                $structureId = $unitMap[$row->housing_unit_id] ?? null;
                if ($structureId) {
                    DB::table('correspondence')->where('id', $row->id)->update(['structure_id' => $structureId]);
                }
            });

        DB::table('common_zone_bookings')
            ->whereNotNull('housing_unit_id')
            ->orderBy('id')
            ->each(function ($row) use ($unitMap) {
                $structureId = $unitMap[$row->housing_unit_id] ?? null;
                if ($structureId) {
                    DB::table('common_zone_bookings')->where('id', $row->id)->update(['structure_id' => $structureId]);
                }
            });

        // ── 6. Drop old columns ──
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropForeign(['housing_unit_id']);
            $table->dropColumn('housing_unit_id');
        });

        Schema::table('correspondence', function (Blueprint $table) {
            $table->dropForeign(['housing_unit_id']);
            $table->dropColumn('housing_unit_id');
        });

        Schema::table('common_zone_bookings', function (Blueprint $table) {
            $table->dropForeign(['housing_unit_id']);
            $table->dropColumn('housing_unit_id');
        });

        // ── 7. Drop old tables ──
        Schema::dropIfExists('resident_housing_unit');
        Schema::dropIfExists('housing_units');
        Schema::dropIfExists('buildings');
    }

    public function down(): void
    {
        // This migration is not reversible in a clean way.
        // Restore tables from backup if needed.
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('housing_units');
        Schema::dropIfExists('resident_housing_unit');
    }
};
