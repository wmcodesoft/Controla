<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Domain\Structure\Data\CreatePetData;
use App\Enums\PetSpecies;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StorePetRequest;
use App\Models\Structure;
use App\Models\StructurePet;
use App\Repositories\StructurePetRepository;
use App\Services\Structure\CreatePetService;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PetController extends Controller
{
    public function __construct(
        private readonly StructurePetRepository $petRepository,
        private readonly CreatePetService $createPetService,
        private readonly TenantContext $tenantContext,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', StructurePet::class);

        $clientId = (int) $this->tenantContext->clientId();
        $pets = $this->petRepository->paginateForClient(
            $clientId,
            $request->string('q')->toString() ?: null,
            $request->integer('structure_id') ?: null,
        );
        $structures = Structure::query()->orderBy('name')->get();
        $species = PetSpecies::options();

        return view('modules.client.pets.index', compact('pets', 'structures', 'species'));
    }

    public function create(): View
    {
        $this->authorize('create', StructurePet::class);

        $structures = Structure::query()->orderBy('name')->get();
        $species = PetSpecies::options();

        return view('modules.client.pets.create', compact('structures', 'species'));
    }

    public function store(StorePetRequest $request): RedirectResponse
    {
        $clientId = (int) $this->tenantContext->clientId();

        $this->createPetService->execute(new CreatePetData(
            clientId: $clientId,
            structureId: (int) $request->validated('structure_id'),
            name: $request->validated('name'),
            species: PetSpecies::from($request->validated('species')),
            breed: $request->validated('breed'),
            isPotentiallyDangerous: $request->boolean('is_potentially_dangerous'),
        ));

        return redirect()
            ->route('client.pets.index')
            ->with('success', 'Mascota registrada en el censo.');
    }

    public function show(StructurePet $pet): View
    {
        $this->authorize('view', $pet);

        $pet->load('structure');

        return view('modules.client.pets.show', compact('pet'));
    }
}
