<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->paginate(15);
        return view('modules.access.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('modules.access.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:locations,code',
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:porteria,edificio,sede,bodega',
            'is_active' => 'boolean',
        ]);

        Location::create($validated);

        return redirect()->route('access.locations.index')
            ->with('success', 'Ubicación creada exitosamente.');
    }

    public function edit(Location $location)
    {
        return view('modules.access.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:locations,code,' . $location->id,
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:porteria,edificio,sede,bodega',
            'is_active' => 'boolean',
        ]);

        $location->update($validated);

        return redirect()->route('access.locations.index')
            ->with('success', 'Ubicación actualizada exitosamente.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('access.locations.index')
            ->with('success', 'Ubicación eliminada exitosamente.');
    }
}
