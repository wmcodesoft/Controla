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
        ]);

        $validated['user_id'] = auth()->id();

        GuardLog::create($validated);

        return redirect()->route('access.guard_logs.index')
            ->with('success', 'Minuta registrada exitosamente.');
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
