<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CorrespondenceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $correspondence = Correspondence::with(['visitor', 'location'])
            ->where('host_id', $request->user()->id)
            ->latest('received_at')
            ->paginate(20);

        return response()->json($correspondence);
    }

    public function show(Correspondence $correspondence, Request $request): JsonResponse
    {
        if ($correspondence->host_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($correspondence->load('visitor', 'location'));
    }
}
