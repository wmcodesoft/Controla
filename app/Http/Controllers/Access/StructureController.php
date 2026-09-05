<?php

declare(strict_types=1);

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Structure;
use App\Repositories\StructureRepository;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class StructureController extends Controller
{
    public function __construct(
        private readonly StructureRepository $structureRepository,
        private readonly TenantContext $tenantContext,
    ) {}

    public function index(Request $request): View
    {
        $clientId = (int) $this->tenantContext->clientId();
        $client = Client::query()->with('structureType')->findOrFail($clientId);
        $search = $request->string('q')->trim()->toString();
        $tree = $this->structureRepository->treeForClient($clientId);
        $census = $this->structureRepository->censusCounts($clientId);

        if ($search !== '') {
            $tree = $this->filterTree($tree, $search);
        }

        return view('modules.access.structures.index', compact('client', 'tree', 'census', 'search'));
    }

    private function filterTree($nodes, string $search): \Illuminate\Support\Collection
    {
        return $nodes->filter(function ($node) use ($search) {
            $matchesName = str_contains(strtolower($node->name), strtolower($search));
            $matchesSecond = $node->second_node_name !== null && str_contains(strtolower($node->second_node_name), strtolower($search));
            $matchesCode = $node->code !== null && str_contains(strtolower($node->code), strtolower($search));
            $matchesType = $node->structureType && str_contains(strtolower($node->structureType->name), strtolower($search));

            if ($node->children->isNotEmpty()) {
                $node->setRelation('children', $this->filterTree($node->children, $search));
            }

            return $matchesName || $matchesSecond || $matchesCode || $matchesType || $node->children->isNotEmpty();
        })->values();
    }
}
