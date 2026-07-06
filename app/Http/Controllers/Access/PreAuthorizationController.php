<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\PreAuthorization;
use App\Models\Visitor;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PreAuthorizationController extends Controller
{
    public function index()
    {
        $preAuthorizations = PreAuthorization::with(['visitor', 'host', 'location'])
            ->latest()
            ->paginate(15);

        return view('modules.access.pre_authorizations.index', compact('preAuthorizations'));
    }

    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        return view('modules.access.pre_authorizations.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'location_id' => 'required|exists:locations,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['host_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['qr_code'] = Str::random(40);
        $validated['expires_at'] = $validated['scheduled_date'] . ' 23:59:59';

        PreAuthorization::create($validated);

        return redirect()->route('access.pre_authorizations.index')
            ->with('success', 'Pre-autorización creada exitosamente.');
    }

    public function show(PreAuthorization $preAuthorization)
    {
        $preAuthorization->load(['visitor', 'host', 'location']);
        return view('modules.access.pre_authorizations.show', compact('preAuthorization'));
    }

    public function destroy(PreAuthorization $preAuthorization)
    {
        $preAuthorization->update(['status' => 'cancelled']);
        return redirect()->route('access.pre_authorizations.index')
            ->with('success', 'Pre-autorización cancelada.');
    }

    public function qr(PreAuthorization $preAuthorization)
    {
        return response()->json(['qr_code' => $preAuthorization->qr_code]);
    }
}
