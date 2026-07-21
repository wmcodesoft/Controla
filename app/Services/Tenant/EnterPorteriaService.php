<?php

declare(strict_types=1);

namespace App\Services\Tenant;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EnterPorteriaService
{
    /** @return Collection<int, Client> */
    public function operableClients(User $user): Collection
    {
        return Client::query()
            ->whereIn('id', $user->assignedClientIds())
            ->where('is_active', true)
            ->where('lifecycle', ClientLifecycle::Active->value)
            ->orderBy('name')
            ->get()
            ->filter(fn (Client $client) => $user->can('operate', $client));
    }

    public function resolve(Request $request): RedirectResponse
    {
        $user = $request->user();
        $clients = $this->operableClients($user);

        if ($clients->isEmpty()) {
            return $this->emptyClientsRedirect($user);
        }

        if ($clients->count() === 1) {
            $client = $clients->first();
            $request->session()->put(config('tenancy.session.active_client_key'), $client->id);

            return redirect()
                ->route('access.dashboard')
                ->with('success', "Operando en: {$client->name}");
        }

        return redirect()
            ->route('company.clients.index', ['modo' => 'operar']);
    }

    private function emptyClientsRedirect(User $user): RedirectResponse
    {
        if ($user->hasRole('super-admin')) {
            return redirect()
                ->route('admin.dashboard')
                ->with('warning', 'No hay conjuntos activos para operar portería.');
        }

        if ($user->hasRole('company-admin')) {
            return redirect()
                ->route('company.clients.index', ['modo' => 'operar'])
                ->with('warning', 'No hay conjuntos activos para operar. Crea o activa un conjunto en cartera.');
        }

        return redirect()
            ->route('company.clients.index', ['modo' => 'operar'])
            ->with('warning', 'No tienes conjuntos asignados para operar portería.');
    }
}
