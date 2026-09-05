<?php

namespace App\Http\Controllers\Access;

use App\Enums\PetSpecies;
use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\StructurePet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = StructurePet::with('structure')->orderBy('name');

        if ($q = $request->string('q')->toString()) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('breed', 'like', "%{$q}%");
            });
        }

        if ($structureId = $request->integer('structure_id')) {
            $query->where('structure_id', $structureId);
        }

        $pets = $query->paginate(20)->withQueryString();
        $structures = Structure::orderBy('name')->get();

        return view('modules.access.pets.index', compact('pets', 'structures'));
    }

    public function create()
    {
        $structures = Structure::orderBy('name')->get();
        $species = PetSpecies::options();

        return view('modules.access.pets.create', compact('structures', 'species'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'structure_id' => 'required|exists:structures,id',
            'name' => 'required|string|max:50',
            'species' => 'required|in:dog,cat,bird,exotic_other',
            'breed' => 'nullable|string|max:50',
            'is_potentially_dangerous' => 'nullable|boolean',
        ]);

        $validated['is_potentially_dangerous'] = $request->boolean('is_potentially_dangerous');
        $validated['species'] = $validated['species'];

        StructurePet::create($validated);

        return redirect()->route('access.pets.index')
            ->with('success', 'Mascota registrada exitosamente.');
    }

    public function show(StructurePet $pet)
    {
        $pet->load('structure');

        return view('modules.access.pets.show', compact('pet'));
    }

    public function destroy(StructurePet $pet)
    {
        $pet->delete();

        return redirect()->route('access.pets.index')
            ->with('success', 'Mascota eliminada.');
    }
}
