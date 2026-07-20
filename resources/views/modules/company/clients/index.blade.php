@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
@endphp

<x-company-layout title="Clientes">
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-indigo-400/80">Cartera</p>
                <h2 class="mt-1 text-3xl font-bold text-white">Clientes / conjuntos</h2>
                <p class="text-sm text-slate-400 mt-1">
                    Cupo {{ $metrics['total'] }}/{{ $metrics['max_clients'] }}
                    · {{ $metrics['package_modality_label'] }}
                    · {{ $metrics['billing_cycle_label'] }} {{ $fmt($metrics['contracted_amount']) }}
                </p>
            </div>
            @can('create', App\Models\Client::class)
                @if (! $metrics['is_quota_full'])
                    <a href="{{ route('company.clients.create') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 shadow-lg shadow-indigo-900/30">
                        Nuevo cliente
                    </a>
                @else
                    <span class="inline-flex items-center rounded-xl border border-amber-700 bg-amber-900/30 px-4 py-2 text-sm text-amber-300">
                        Cupo del paquete agotado
                    </span>
                @endif
            @endcan
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4 flex flex-wrap gap-4 text-sm">
            <div>
                <span class="text-slate-500">Licencia</span>
                <span class="ml-2 text-white font-medium">{{ $metrics['package_label'] }}</span>
            </div>
            <div>
                <span class="text-slate-500">Restantes</span>
                <span class="ml-2 text-indigo-300 font-medium">{{ $metrics['clients_remaining'] }}</span>
            </div>
            <div>
                <span class="text-slate-500">Portafolio</span>
                <span class="ml-2 text-slate-300">Ilimitado por conjunto</span>
            </div>
        </div>

        @if (session('error'))
            <div class="rounded-xl border border-red-800 bg-red-950/40 px-4 py-3 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        <div class="rounded-2xl border border-slate-800 overflow-hidden bg-slate-900 shadow-xl shadow-black/10">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Modalidad</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Login APP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-4">
                                <p class="font-medium text-white">{{ $client->name }}</p>
                                <p class="text-xs text-slate-500">{{ $client->slug }}</p>
                            </td>
                            <td class="px-4 py-4 text-slate-300">
                                {{ $client->securityCompany?->package_modality?->label() ?? $metrics['package_modality_label'] }}
                            </td>
                            <td class="px-4 py-4 font-mono text-xs text-indigo-300">@{{ $client->login_suffix }}</td>
                            <td class="px-4 py-4">
                                @if ($client->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-900/50 px-2 py-0.5 text-xs text-emerald-300">Activo</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-800 px-2 py-0.5 text-xs text-slate-400">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right space-x-3">
                                <a href="{{ route('company.clients.show', $client) }}" class="text-indigo-400 hover:text-indigo-300">Detalle</a>
                                @can('operate', $client)
                                <form action="{{ route('company.clients.activate', $client) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-400 hover:text-emerald-300">Operar</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $clients->links() }}</div>
    </div>
</x-company-layout>
