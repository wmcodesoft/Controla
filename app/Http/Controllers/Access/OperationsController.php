<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Correspondence;
use App\Models\PreAuthorization;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\Structure;

class OperationsController extends Controller
{
    public function index()
    {
        $activeEntries = AccessLog::where('status', 'active')->count();
        $todayEntries = AccessLog::whereDate('entry_time', today())->count();
        $pendingCorrespondence = Correspondence::where('status', 'pending')->count();
        $pendingPreAuthorizations = PreAuthorization::where('status', 'pending')
            ->whereDate('scheduled_date', '>=', today())
            ->count();
        $totalVisitors = Visitor::count();
        $totalResidents = Resident::count();

        $peopleInside = AccessLog::with(['visitor', 'resident', 'structure', 'location', 'vehicle', 'user'])
            ->where('status', 'active')
            ->latest('entry_time')
            ->get()
            ->map(function ($log) {
                $hoursInside = $log->entry_time->diffInHours(now());
                $log->hours_inside = $hoursInside;
                $log->alert_long_stay = $hoursInside >= (int) config('access.alerts.long_stay_hours');
                $log->person_name = $log->visitor?->full_name ?? $log->resident?->full_name ?? $log->user?->name ?? '-';
                $log->person_doc = $log->visitor && $log->visitor->document_type
                    ? $log->visitor->document_type . ' ' . $log->visitor->document_number
                    : ($log->resident && $log->resident->document_type
                        ? $log->resident->document_type . ' ' . $log->resident->document_number
                        : '-');
                $log->person_type = $log->access_type === 'visitor_vehicle' ? 'Visitante Vehicular'
                    : ($log->access_type === 'resident_vehicle' ? 'Persona Vehicular'
                    : ($log->access_type === 'resident' ? 'Persona' : 'Visitante'));
                $log->destination = $log->structure?->full_path ?? '-';
                return $log;
            });

        $recentLogs = AccessLog::with(['visitor', 'resident', 'host', 'location'])
            ->latest('entry_time')
            ->take(10)
            ->get();

        // Chart data: daily entries for last 7 days
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('D');
            $dailyData[] = AccessLog::whereDate('entry_time', $date)->count();
        }

        // Chart data: access type distribution
        $typeLabels = ['Visitante', 'Vehicular', 'Persona'];
        $typeData = [
            AccessLog::where('access_type', 'visitor')->count(),
            AccessLog::where('access_type', 'visitor_vehicle')->count(),
            AccessLog::whereIn('access_type', ['resident', 'resident_vehicle'])->count(),
        ];

        // Chart data: hourly distribution for today
        $hourlyLabels = [];
        $hourlyData = [];
        for ($h = 0; $h < 24; $h++) {
            $hourlyLabels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $hourlyData[] = AccessLog::whereDate('entry_time', today())
                ->whereTime('entry_time', '>=', str_pad($h, 2, '0') . ':00:00')
                ->whereTime('entry_time', '<', str_pad(($h + 1) % 24, 2, '0') . ':00:00')
                ->count();
        }

        return view('modules.access.operations', compact(
            'activeEntries', 'todayEntries', 'pendingCorrespondence',
            'pendingPreAuthorizations', 'totalVisitors', 'totalResidents',
            'peopleInside', 'recentLogs',
            'dailyLabels', 'dailyData', 'typeLabels', 'typeData',
            'hourlyLabels', 'hourlyData'
        ));
    }
}
