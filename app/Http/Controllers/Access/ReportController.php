<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Visitor;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessLog::with(['visitor', 'host', 'location']);

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

        $logs = $query->latest('entry_time')->paginate(20);

        $totalEntries = AccessLog::count();
        $activeEntries = AccessLog::where('status', 'active')->count();
        $totalVisitors = Visitor::count();
        $avgDuration = AccessLog::whereNotNull('exit_time')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, entry_time, exit_time)) as avg_minutes')
            ->value('avg_minutes');

        $locations = \App\Models\Location::where('is_active', true)->get();

        return view('modules.access.reports.index', compact(
            'logs', 'totalEntries', 'activeEntries', 'totalVisitors', 'avgDuration', 'locations'
        ));
    }
}
