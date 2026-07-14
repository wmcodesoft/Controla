<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Visitor;
use App\Models\Location;
use App\Exports\AccessLogsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessLog::with(['visitor', 'host', 'location', 'resident']);

        if ($request->filled('date_from')) {
            $query->whereDate('entry_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('entry_time', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('access_type')) {
            $query->where('access_type', $request->access_type);
        }

        $logs = $query->latest('entry_time')->paginate(20)->withQueryString();

        $totalEntries = AccessLog::count();
        $activeEntries = AccessLog::where('status', 'active')->count();
        $todayEntries = AccessLog::whereDate('entry_time', today())->count();
        $totalVisitors = Visitor::count();
        $avgDuration = AccessLog::whereNotNull('exit_time')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, entry_time, exit_time)) as avg_minutes')
            ->value('avg_minutes');

        $locations = Location::where('is_active', true)->get();

        return view('modules.access.reports.index', compact(
            'logs', 'totalEntries', 'activeEntries', 'todayEntries', 'totalVisitors', 'avgDuration', 'locations'
        ));
    }

    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(
            new AccessLogsExport($request),
            'reporte-accesos.xlsx',
        );
    }
}
