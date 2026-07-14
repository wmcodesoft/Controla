<?php

declare(strict_types=1);

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use App\Models\PreAuthorization;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $preAuthorizations = PreAuthorization::with('visitor')
            ->where('host_id', $user->id)
            ->latest('scheduled_date')
            ->take(10)
            ->get();

        $pendingCorrespondence = Correspondence::with('visitor')
            ->where('host_id', $user->id)
            ->where('status', 'pending')
            ->latest('received_at')
            ->take(10)
            ->get();

        return view('modules.resident.dashboard', compact('preAuthorizations', 'pendingCorrespondence'));
    }
}
