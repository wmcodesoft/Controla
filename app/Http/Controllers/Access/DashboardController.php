<?php
namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\PreAuthorization;
use App\Models\Correspondence;
use App\Models\Building;
use App\Models\HousingUnit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeEntries = AccessLog::where('status', 'active')->count();
        $todayEntries = AccessLog::whereDate('entry_time', today())->count();
        $totalVisitors = Visitor::count();
        $totalResidents = Resident::count();
        $totalHousingUnits = HousingUnit::count();
        $totalBuildings = Building::count();
        $pendingCorrespondence = Correspondence::where('status', 'pending')->count();
        $pendingPreAuthorizations = PreAuthorization::where('status', 'pending')
            ->whereDate('scheduled_date', '>=', today())
            ->count();

        $recentLogs = AccessLog::with(['visitor', 'resident', 'host', 'location'])
            ->latest('entry_time')
            ->take(10)
            ->get();

        return view('modules.access.dashboard', compact(
            'activeEntries', 'todayEntries', 'totalVisitors', 'totalResidents',
            'totalHousingUnits', 'totalBuildings',
            'pendingCorrespondence', 'pendingPreAuthorizations', 'recentLogs'
        ));
    }
}
