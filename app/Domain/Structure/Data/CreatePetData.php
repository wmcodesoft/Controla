<?php

declare(strict_types=1);

namespace App\Domain\Structure\Data;

use App\Enums\PetSpecies;

final readonly class CreatePetData
{
    public function __construct(
        public int $clientId,
        public int $structureId,
        public string $name,
        public PetSpecies $species,
        public ?string $breed = null,
        public bool $isPotentiallyDangerous = false,
        public ?string $vaccinationCardPath = null,
    ) {}
}
