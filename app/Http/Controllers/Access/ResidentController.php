<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Structure;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::with('structure')->latest()->paginate(15);
        return view('modules.access.residents.index', compact('residents'));
    }

    public function create()
    {
        $structures = Structure::where('is_active', true)
            ->with('structureType', 'parent')
            ->orderBy('name')
            ->get();
        return view('modules.access.residents.create', compact('structures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:20',
            'document_number' => 'required|string|max:50|unique:residents,document_number',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'blood_type' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'resident_type' => 'required|in:propietario,inquilino,familiar,empleado_domestico',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'structure_id' => 'nullable|exists:structures,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $resident = Resident::create($validated);

        return redirect()->route('access.residents.index')
            ->with('success', 'Persona creada exitosamente.');
    }

    public function show(Resident $resident)
    {
        $resident->load(['structure', 'vehicles', 'accessLogs' => function ($q) {
            $q->latest()->take(20);
        }]);
        return view('modules.access.residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        $structures = Structure::where('is_active', true)
            ->orderBy('name')
            ->get();
        $resident->load('structure');
        return view('modules.access.residents.edit', compact('resident', 'structures'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:20',
            'document_number' => 'required|string|max:50|unique:residents,document_number,' . $resident->id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'blood_type' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'resident_type' => 'required|in:propietario,inquilino,familiar,empleado_domestico',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'structure_id' => 'nullable|exists:structures,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $resident->update($validated);

        return redirect()->route('access.residents.index')
            ->with('success', 'Persona actualizada exitosamente.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('access.residents.index')
            ->with('success', 'Persona eliminada.');
    }

    public function searchJson(Request $request)
    {
        $query = $request->get('q');
        $residents = Resident::with('structure')
            ->where(function ($q) use ($query) {
                $q->where('document_number', 'like', "%{$query}%")
                  ->orWhere('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$query}%");
            })
            ->where('is_active', true)
            ->take(10)
            ->get();

        return response()->json($residents);
    }

    public function searchStructuresJson(Request $request)
    {
        $query = $request->get('q');
        $structures = Structure::with('structureType', 'parent')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%")
                  ->orWhere('second_node_name', 'like', "%{$query}%");
            })
            ->where('is_active', true)
            ->take(10)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'label' => $s->full_path,
                    'name' => $s->name,
                    'second_node_name' => $s->second_node_name,
                    'type' => $s->structureType?->name ?? '',
                ];
            });

        return response()->json($structures);
    }

    public function addVehicle(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles,plate',
            'brand' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'type' => 'required|in:carro,moto,camion',
        ]);

        $validated['resident_id'] = $resident->id;
        Vehicle::create($validated);

        return back()->with('success', 'Vehículo asignado a la persona.');
    }

    public function removeVehicle(Resident $resident, Vehicle $vehicle)
    {
        if ($vehicle->resident_id !== $resident->id) {
            return back()->with('error', 'El vehículo no pertenece a esta persona.');
        }
        $vehicle->update(['resident_id' => null]);
            return back()->with('success', 'Vehículo desasignado de la persona.');
    }
}
