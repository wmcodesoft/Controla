<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blocklist;
use Illuminate\Http\Request;

class BlocklistController extends Controller
{
    public function index()
    {
        $entries = Blocklist::with('blocker')
            ->latest('blocked_at')
            ->paginate(20);

        return view('modules.client.blocklist.index', compact('entries'));
    }

    public function destroy(Blocklist $blocklist)
    {
        $blocklist->update(['is_active' => false]);

        return redirect()->route('client.blocklist.index')
            ->with('success', 'Bloqueo removido.');
    }
}
