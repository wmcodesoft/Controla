<?php

declare(strict_types=1);

namespace App\Services\Structure;

use App\Domain\Structure\Data\CreatePetData;
use App\Models\StructurePet;
use Illuminate\Support\Facades\DB;

final class CreatePetService
{
    public function execute(CreatePetData $data): StructurePet
    {
        return DB::transaction(function () use ($data): StructurePet {
            return StructurePet::query()->create([
                'client_id' => $data->clientId,
                'structure_id' => $data->structureId,
                'name' => $data->name,
                'species' => $data->species,
                'breed' => $data->breed,
                'is_potentially_dangerous' => $data->isPotentiallyDangerous,
                'vaccination_card_path' => $data->vaccinationCardPath,
            ]);
        });
    }
}
