<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureCompanyUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        if ($user->hasRole('company-admin') && $user->security_company_id) {
            return $next($request);
        }

        if ($request->routeIs('company.clients.index') && $request->query('modo') === 'operar') {
            if ($user->can('access.dashboard') && count($user->assignedClientIds()) > 0) {
                return $next($request);
            }
        }

        abort(403, 'Acceso restringido al panel de empresa.');
    }
}
