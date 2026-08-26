<?php

declare(strict_types=1);

namespace App\Services\Structure;

use App\Domain\Structure\Data\CreateStructureData;
use App\Models\Client;
use App\Models\Structure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CreateStructureService
{
    public function execute(CreateStructureData $data): Structure
    {
        return DB::transaction(function () use ($data): Structure {
            $client = Client::query()->findOrFail($data->clientId);
            $structureTypeId = $client->structure_type_id ?? $data->structureTypeId;

            if ($structureTypeId === null) {
                throw ValidationException::withMessages([
                    'name' => 'Este cliente no tiene tipo de estructura asignado. Configúralo en la ficha del cliente.',
                ]);
            }

            // Lógica de nombres: name = primer nodo, second_node_name = segundo nodo
            $parentName = null;
            $secondNodeName = null;

            if ($data->parentId !== null) {
                $parent = Structure::query()->find($data->parentId);

                if ($parent === null) {
                    throw ValidationException::withMessages([
                        'parentId' => 'El padre especificado no existe.',
                    ]);
                }

                // Validación: un subnodo no puede tener a su vez hijos (profundidad máxima 2)
                if ($parent->parent_id !== null) {
                    throw ValidationException::withMessages([
                        'parentId' => 'No se permiten subniveles deeper than 2. La estructura máxima es: Padre → Subnodo. No se pueden añadir más niveles.',
                    ]);
                }

                $parentName = $parent->name;
                $secondNodeName = $data->name;
            }

            return Structure::query()->create([
                'client_id' => $data->clientId,
                'parent_id' => $data->parentId,
                'name' => $data->parentId !== null ? $parentName : $data->name,
                'second_node_name' => $secondNodeName,
                'code' => $data->code,
                'structure_type_id' => (int) $structureTypeId,
                'max_occupancy' => $data->maxOccupancy,
                'is_active' => $data->isActive,
                'metadata' => $data->metadata,
            ]);
        });
    }
}
