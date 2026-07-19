<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\GuardLog;
use App\Models\Location;
use Illuminate\Http\Request;

class GuardLogController extends Controller
{
    public function index()
    {
        $logs = GuardLog::with(['user', 'location'])
            ->latest('log_time')
            ->paginate(15);

        return view('modules.access.guard_logs.index', compact('logs'));
    }

    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        return view('modules.access.guard_logs.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'log_time' => 'required|date',
            'type' => 'required|in:novedad,turno,incidente,general',
            'shift_type' => 'required|in:diurno,nocturno',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'signed' => 'accepted',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['signed_at'] = $request->boolean('signed') ? now() : null;

        GuardLog::create($validated);

        return redirect()->route('access.guard_logs.index')
            ->with('success', 'Minuta registrada exitosamente.');
    }

    public function panic(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        GuardLog::create([
            'client_id' => auth()->user()->primary_client_id,
            'user_id' => auth()->id(),
            'location_id' => $request->location_id,
            'log_time' => now(),
            'type' => 'incidente',
            'shift_type' => now()->hour >= 6 && now()->hour < 18 ? 'diurno' : 'nocturno',
            'description' => '🚨 PANIC: ' . $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_panic' => true,
            'signed_at' => now(),
        ]);

        return redirect()->route('access.guard_logs.index')
            ->with('success', '🚨 Alerta de pánico registrada. Personal notificado.');
    }

    public function show(GuardLog $guardLog)
    {
        $guardLog->load(['user', 'location']);
        return view('modules.access.guard_logs.show', compact('guardLog'));
    }

    public function destroy(GuardLog $guardLog)
    {
        $guardLog->delete();
        return redirect()->route('access.guard_logs.index')
            ->with('success', 'Minuta eliminada.');
    }
}
