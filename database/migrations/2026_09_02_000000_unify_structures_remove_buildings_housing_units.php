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
        // ── 1. Add location_id to structures (skip if exists) ──
        if (!Schema::hasColumn('structures', 'location_id')) {
            Schema::table('structures', function (Blueprint $table) {
                $table->foreignId('location_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
            });
        }

        // ── 2. Add structure_id to tables (skip if exists) ──
        foreach (['access_logs', 'correspondence', 'common_zone_bookings'] as $table) {
            if (!Schema::hasColumn($table, 'structure_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('structure_id')->nullable()->after('housing_unit_id')->constrained()->nullOnDelete();
                });
            }
        }

        // ── 3. Migrate data: buildings → structures ──
        if (Schema::hasTable('buildings')) {
            $buildings = DB::table('buildings')->whereNull('deleted_at')->get();
            $buildingMap = [];

            foreach ($buildings as $building) {
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

            // ── 4. Migrate data: housing_units → structures ──
            if (Schema::hasTable('housing_units')) {
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

                    $metadata = json_encode(array_filter([
                        'floor' => $unit->floor,
                        'notes' => $unit->notes,
                        'legacy_housing_unit_id' => $unit->id,
                    ]));

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

                // ── 5. Populate structure_id ──
                foreach (['access_logs', 'correspondence', 'common_zone_bookings'] as $table) {
                    if (Schema::hasColumn($table, 'housing_unit_id') && Schema::hasColumn($table, 'structure_id')) {
                        DB::table($table)
                            ->whereNotNull('housing_unit_id')
                            ->whereNull('structure_id')
                            ->orderBy('id')
                            ->each(function ($row) use ($unitMap, $table) {
                                $structureId = $unitMap[$row->housing_unit_id] ?? null;
                                if ($structureId) {
                                    DB::table($table)->where('id', $row->id)->update(['structure_id' => $structureId]);
                                }
                            });
                    }
                }

                // ── 6. Drop old columns (safe) ──
                foreach (['access_logs', 'correspondence', 'common_zone_bookings'] as $tbl) {
                    if (Schema::hasColumn($tbl, 'housing_unit_id')) {
                        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = '{$tbl}' AND COLUMN_NAME = 'housing_unit_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                        foreach ($foreignKeys as $fk) {
                            DB::statement("ALTER TABLE `{$tbl}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                        }
                        Schema::table($tbl, function (Blueprint $table) {
                            $table->dropColumn('housing_unit_id');
                        });
                    }
                }

                // ── 7. Drop old tables ──
                Schema::dropIfExists('resident_housing_unit');
                Schema::dropIfExists('housing_units');
                Schema::dropIfExists('buildings');
            }
        }
    }

    public function down(): void
    {
        // Not reversible
    }
};
