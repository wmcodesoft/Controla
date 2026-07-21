<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Tenant\EnterPorteriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PorteriaController extends Controller
{
    public function __construct(
        private readonly EnterPorteriaService $enterPorteriaService,
    ) {}

    public function enter(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->can('access.dashboard'), 403);

        return $this->enterPorteriaService->resolve($request);
    }
}
