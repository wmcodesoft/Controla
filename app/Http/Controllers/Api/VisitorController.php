<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class VisitorController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');

        if (! $query || strlen($query) < 2) {
            return response()->json([]);
        }

        $visitors = Visitor::where(function ($q) use ($query): void {
            $q->where('document_number', 'like', "%{$query}%")
                ->orWhere('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%");
        })
            ->limit(10)
            ->get(['id', 'document_type', 'document_number', 'first_name', 'last_name', 'company']);

        return response()->json($visitors);
    }
}
