<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::query()->latest()->paginate(15);

        return view('modules.client.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('modules.client.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('locations', 'code')],
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'geo_radius_m' => 'nullable|integer|min:10',
            'is_active' => 'boolean',
        ]);

        if (($validated['latitude'] ?? null) !== null xor ($validated['longitude'] ?? null) !== null) {
            return back()->withErrors(['geo' => 'Latitud y longitud deben ir juntas.'])->withInput();
        }

        Location::query()->create([
            ...$validated,
            'type' => 'access_point',
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('client.locations.index')
            ->with('success', 'Punto de acceso creado correctamente.');
    }

    public function edit(Location $location)
    {
        return view('modules.client.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'code' => [
                'required', 'string', 'max:20',
                Rule::unique('locations', 'code')->ignore($location->id),
            ],
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'geo_radius_m' => 'nullable|integer|min:10',
            'is_active' => 'boolean',
        ]);

        if (($validated['latitude'] ?? null) !== null xor ($validated['longitude'] ?? null) !== null) {
            return back()->withErrors(['geo' => 'Latitud y longitud deben ir juntas.'])->withInput();
        }

        $location->update([
            ...$validated,
            'type' => 'access_point',
            'is_active' => $request->boolean('is_active', $location->is_active),
        ]);

        return redirect()->route('client.locations.index')
            ->with('success', 'Punto de acceso actualizado correctamente.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('client.locations.index')
            ->with('success', 'Punto de acceso eliminado correctamente.');
    }
}
