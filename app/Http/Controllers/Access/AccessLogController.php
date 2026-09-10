<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\Vehicle;
use App\Models\Location;
use App\Models\Structure;
use App\Models\User;
use App\Services\Access\AuditLogger;
use App\Services\Access\BlocklistGuard;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    public function index()
    {
        $activeLogs = AccessLog::with(['visitor', 'resident', 'structure', 'host', 'location', 'vehicle'])
            ->where('status', 'active')
            ->latest('entry_time')
            ->get()
            ->map(function ($log) {
                $log->hours_inside = $log->entry_time->diffInHours(now());
                $log->alert_long_stay = $log->hours_inside >= config('access.alerts.long_stay_hours');
                return $log;
            });

        $todayLogs = AccessLog::with(['visitor', 'resident', 'structure', 'host', 'location'])
            ->whereDate('entry_time', today())
            ->latest('entry_time')
            ->paginate(20);

        return view('modules.access.logs.index', compact('activeLogs', 'todayLogs'));
    }

    public function historical(Request $request)
    {
        $query = AccessLog::with(['visitor', 'resident', 'structure', 'host', 'location', 'vehicle'])
            ->latest('entry_time');

        if ($request->filled('date_from')) {
            $query->whereDate('entry_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('entry_time', '<=', $request->date_to);
        }
        if ($request->filled('person_type')) {
            if ($request->person_type === 'resident') {
                $query->whereNotNull('resident_id');
            } elseif ($request->person_type === 'visitor') {
                $query->whereNotNull('visitor_id');
            }
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('resident', fn ($r) => $r->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('document_number', 'like', "%{$search}%"))
                  ->orWhereHas('visitor', fn ($v) => $v->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('document_number', 'like', "%{$search}%"));
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('modules.access.logs.historical', compact('logs'));
    }

    public function entry()
    {
        $locations = Location::where('is_active', true)->get();
        $hosts = Resident::whereNull('deleted_at')->orderBy('first_name')->get();
        $structures = Structure::where('is_active', true)
            ->with('structureType')
            ->orderBy('name')
            ->get();
        return view('modules.access.logs.entry', compact('locations', 'hosts', 'structures'));
    }

    public function exitPage()
    {
        return view('modules.access.logs.exit');
    }

    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:50',
            'person_type' => 'required|in:resident,visitor',
            'person_id' => 'required|integer',
            'structure_id' => 'nullable|exists:structures,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'host_id' => 'nullable|exists:residents,id',
            'location_id' => 'required|exists:locations,id',
            'access_type' => 'required|in:visitor,visitor_vehicle,resident,resident_vehicle',
            'purpose' => 'nullable|string|max:255',
            'company_visited' => 'nullable|string|max:150',
            'screening_temp' => 'nullable|numeric|min:34|max:42',
            'notes' => 'nullable|string',
        ]);

        if ($validated['person_type'] === 'visitor') {
            $visitor = Visitor::find($validated['person_id']);
            if (! $visitor) {
                return back()->withErrors(['document_number' => 'Visitante no encontrado.'])->withInput();
            }
            $visitorBlock = app(BlocklistGuard::class)->checkPerson(visitor: $visitor);
            if ($visitorBlock !== null) {
                return back()->withErrors(['document_number' => '🚫 Ingreso bloqueado: '.$visitorBlock->reason])->withInput();
            }
            $validated['visitor_id'] = $visitor->id;
        } else {
            $resident = Resident::find($validated['person_id']);
            if (! $resident) {
                return back()->withErrors(['document_number' => 'Persona no encontrada.'])->withInput();
            }
            $validated['resident_id'] = $resident->id;
            if (! isset($validated['structure_id'])) {
                $validated['structure_id'] = $resident->structure_id;
            }
            if (empty($validated['host_id'])) {
                $validated['host_id'] = $resident->id;
            }
        }

        if (! empty($validated['vehicle_id'])) {
            $vehicleBlock = app(BlocklistGuard::class)->checkVehicle(
                vehicle: Vehicle::find($validated['vehicle_id'])
            );
            if ($vehicleBlock !== null) {
                return back()->withErrors(['vehicle_id' => '🚫 Vehículo bloqueado: '.$vehicleBlock->reason])->withInput();
            }
        }

        $validated['authorized_by'] = auth()->id();
        $validated['entry_time'] = now();
        $validated['status'] = 'active';

        unset($validated['person_type'], $validated['person_id'], $validated['document_number']);

        $log = AccessLog::create($validated);

        app(AuditLogger::class)->record($log, 'access.entry', null, [
            'access_type' => $log->access_type,
            'visitor_id' => $log->visitor_id,
            'resident_id' => $log->resident_id,
            'location_id' => $log->location_id,
            'entry_time' => $log->entry_time->toDateTimeString(),
        ]);

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

        app(AuditLogger::class)->record($accessLog, 'access.exit', [
            'status' => $accessLog->getOriginal('status'),
        ], [
            'exit_time' => $accessLog->exit_time?->toDateTimeString(),
            'status' => $accessLog->status,
            'has_custody' => $accessLog->has_custody,
        ]);

        return redirect()->route('access.logs.index')
            ->with('success', 'Salida registrada exitosamente.');
    }

    public function scanExit(Request $request)
    {
        $request->validate([
            'document_number' => 'required_if:log_id,null|string|max:50',
            'log_id' => 'nullable|exists:access_logs,id',
            'has_custody' => 'boolean',
            'custody_description' => 'required_if:has_custody,1|string|max:1000',
            'custody_receiver_name' => 'nullable|string|max:255',
        ]);

        if (! empty($request->log_id)) {
            $log = AccessLog::with(['visitor', 'resident', 'vehicle', 'location'])
                ->where('status', 'active')
                ->find($request->log_id);

            if ($log === null) {
                return response()->json(['error' => 'El registro ya tiene salida o no existe.'], 422);
            }

            $data = [
                'exit_time' => now(),
                'status' => 'completed',
            ];

            if ($request->boolean('has_custody')) {
                $data['has_custody'] = true;
                $data['custody_description'] = $request->custody_description;
                $data['custody_receiver_name'] = $request->custody_receiver_name;
                $data['custody_received_at'] = now();
            }

            $log->update($data);

            app(AuditLogger::class)->record($log, 'access.exit', ['status' => 'active'], [
                'exit_time' => $log->exit_time?->toDateTimeString(),
                'status' => 'completed',
                'via' => 'kiosco',
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Salida registrada exitosamente.',
                'id' => $log->id,
            ]);
        }

        $logs = $this->activeLogsForDocument($request->document_number);

        if ($logs->isEmpty()) {
            return response()->json([
                'found' => false,
                'message' => 'No hay ingresos activos para el documento ingresado.',
            ]);
        }

        return response()->json([
            'found' => true,
            'matches' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'name' => $log->visitor?->full_name ?? $log->resident?->full_name ?? $log->user?->name ?? '-',
                    'entry_time' => $log->entry_time->format('d/m/Y H:i'),
                    'duration_hours' => (int) $log->entry_time->diffInHours(now()),
                    'destination' => $log->structure?->full_path ?? $log->location?->name,
                    'has_vehicle' => $log->vehicle?->plate ?? ($log->access_type === 'resident_vehicle' ? 'Sí' : null),
                ];
            }),
        ]);
    }

    private function activeLogsForDocument(string $documentNumber)
    {
        $visitorIds = Visitor::whereNull('deleted_at')
            ->where('document_number', $documentNumber)
            ->pluck('id');

        $residentIds = Resident::where('document_number', $documentNumber)->pluck('id');

        $vehicleIds = Vehicle::whereRaw('upper(plate) = ?', [strtoupper($documentNumber)])->pluck('id');

        return AccessLog::with(['visitor', 'resident', 'vehicle', 'location', 'structure'])
            ->where('status', 'active')
            ->where(function ($q) use ($visitorIds, $residentIds, $vehicleIds) {
                $q->whereIn('visitor_id', $visitorIds)
                    ->orWhereIn('resident_id', $residentIds)
                    ->orWhereIn('vehicle_id', $vehicleIds);
            })
            ->latest('entry_time')
            ->get();
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

    public function lookupDocument(Request $request)
    {
        $request->validate([
            'document_number' => 'required|string|max:50',
        ]);

        $doc = trim($request->document_number);
        $clientId = (int) app(\App\Support\Tenancy\TenantContext::class)->clientId();

        // 1. Check if it's a resident
        $resident = Resident::where('client_id', $clientId)
            ->where('document_number', $doc)
            ->whereNull('deleted_at')
            ->with('structure')
            ->first();

        if ($resident) {
            return response()->json([
                'found' => true,
                'type' => 'resident',
                'person' => [
                    'id' => $resident->id,
                    'document_type' => $resident->document_type,
                    'document_number' => $resident->document_number,
                    'first_name' => $resident->first_name,
                    'last_name' => $resident->last_name,
                    'full_name' => $resident->full_name,
                    'structure_id' => $resident->structure_id,
                    'structure_name' => $resident->structure?->full_path,
                ],
            ]);
        }

        // 2. Check if it's an existing visitor
        $visitor = Visitor::where('client_id', $clientId)
            ->where('document_number', $doc)
            ->whereNull('deleted_at')
            ->first();

        if ($visitor) {
            $lastLog = AccessLog::where('visitor_id', $visitor->id)
                ->latest('entry_time')
                ->first();

            return response()->json([
                'found' => true,
                'type' => 'visitor',
                'person' => [
                    'id' => $visitor->id,
                    'document_type' => $visitor->document_type,
                    'document_number' => $visitor->document_number,
                    'first_name' => $visitor->first_name,
                    'last_name' => $visitor->last_name,
                    'full_name' => $visitor->full_name,
                    'phone' => $visitor->phone,
                ],
                'last_visit' => $lastLog ? [
                    'purpose' => $lastLog->purpose,
                    'company_visited' => $lastLog->company_visited,
                    'host_id' => $lastLog->host_id,
                    'structure_id' => $lastLog->structure_id,
                    'vehicle_id' => $lastLog->vehicle_id,
                ] : null,
            ]);
        }

        // 3. Unknown — return status for front-end to handle
        return response()->json([
            'found' => false,
            'status' => 'unknown',
            'document_number' => $doc,
        ]);
    }

    public function createVisitor(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $clientId = (int) app(\App\Support\Tenancy\TenantContext::class)->clientId();

        $visitor = Visitor::create([
            'client_id' => $clientId,
            'document_type' => 'CC',
            'document_number' => $validated['document_number'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? '',
            'phone' => $validated['phone'] ?? null,
            'visitor_type' => 'occasional',
        ]);

        return response()->json([
            'ok' => true,
            'person' => [
                'id' => $visitor->id,
                'document_type' => $visitor->document_type,
                'document_number' => $visitor->document_number,
                'first_name' => $visitor->first_name,
                'last_name' => $visitor->last_name,
                'full_name' => $visitor->full_name,
            ],
        ]);
    }
}
