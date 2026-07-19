<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\Location;
use App\Models\HousingUnit;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    public function index()
    {
        $activeLogs = AccessLog::with(['visitor', 'resident', 'housingUnit.building', 'host', 'location', 'vehicle'])
            ->where('status', 'active')
            ->latest('entry_time')
            ->get();

        $todayLogs = AccessLog::with(['visitor', 'resident', 'housingUnit.building', 'host', 'location'])
            ->whereDate('entry_time', today())
            ->latest('entry_time')
            ->paginate(20);

        return view('modules.access.logs.index', compact('activeLogs', 'todayLogs'));
    }

    public function entry()
    {
        $locations = Location::where('is_active', true)->get();
        $hosts = User::role('anfitrion')->get();
        $buildings = \App\Models\Building::where('is_active', true)->get();
        $housingUnits = HousingUnit::where('is_active', true)->with('building')->get();
        return view('modules.access.logs.entry', compact('locations', 'hosts', 'buildings', 'housingUnits'));
    }

    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'housing_unit_id' => 'nullable|exists:housing_units,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'host_id' => 'required|exists:users,id',
            'location_id' => 'required|exists:locations,id',
            'access_type' => 'required|in:visitor,visitor_vehicle',
            'purpose' => 'nullable|string|max:255',
            'company_visited' => 'nullable|string|max:150',
            'screening_temp' => 'nullable|numeric|min:34|max:42',
            'notes' => 'nullable|string',
        ]);

        $validated['authorized_by'] = auth()->id();
        $validated['entry_time'] = now();
        $validated['status'] = 'active';

        AccessLog::create($validated);

        return redirect()->route('access.logs.index')
            ->with('success', 'Ingreso registrado exitosamente.');
    }

    public function markExit(Request $request, AccessLog $accessLog)
    {
        if ($accessLog->status !== 'active') {
            return back()->with('error', 'Este registro ya tiene una salida registrada.');
        }

        $data = [
            'exit_time' => now(),
            'status' => 'completed',
        ];

        if ($request->boolean('has_custody')) {
            $custodyData = $request->validate([
                'custody_description' => 'required|string|max:1000',
                'custody_receiver_name' => 'nullable|string|max:255',
            ]);
            $data['has_custody'] = true;
            $data['custody_description'] = $custodyData['custody_description'];
            $data['custody_receiver_name'] = $custodyData['custody_receiver_name'];
            $data['custody_received_at'] = now();
        }

        $accessLog->update($data);

        return redirect()->route('access.logs.index')
            ->with('success', 'Salida registrada exitosamente.');
    }

    public function bulkExit(Request $request)
    {
        $count = AccessLog::where('status', 'active')
            ->whereDate('entry_time', '<=', today())
            ->update([
                'exit_time' => now(),
                'status' => 'completed',
            ]);

        return redirect()->route('access.logs.index')
            ->with('success', "Salida masiva: {$count} registro(s) actualizado(s).");
    }
}
