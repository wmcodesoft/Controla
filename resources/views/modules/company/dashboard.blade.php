@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
    $endsAt = $metrics['package_ends_at'] ?? null;
@endphp

<x-company-layout title="Resumen empresa">
    <div class="space-y-8">
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-gradient-to-br from-indigo-950/50 via-slate-900 to-slate-950 p-8">
            <div class="absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="relative flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-300/80">Tu licencia Controla</p>
                    <h2 class="mt-2 text-3xl font-bold text-white tracking-tight">{{ $metrics['package_label'] }}</h2>
                    <p class="mt-2 text-sm text-slate-400">
                        {{ $metrics['package_modality_label'] }}
                        · Facturación {{ $metrics['billing_cycle_label'] }}
                        · Estado {{ $metrics['subscription_status_label'] }}
                    </p>
                    @if ($endsAt)
                        <p class="mt-1 text-xs text-slate-500">Vigencia hasta {{ $endsAt->format('d/m/Y') }}</p>
                    @endif
                </div>
                <div class="text-left lg:text-right">
                    <p class="text-xs uppercase text-slate-500">Importe contratado</p>
                    <p class="text-3xl font-bold text-white">{{ $fmt($metrics['contracted_amount']) }}</p>
                    <p class="text-sm text-slate-400">{{ $metrics['billing_cycle'] === 'annual' ? 'por año' : 'por mes' }}</p>
                </div>
            </div>

            <div class="relative mt-8">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-slate-400">Cupo de conjuntos</span>
                    <span class="font-semibold text-white">{{ $metrics['total'] }} / {{ $metrics['max_clients'] }}</span>
                </div>
                <div class="h-2.5 rounded-full bg-slate-800 overflow-hidden">
                    <div class="h-full rounded-full {{ $metrics['usage_ratio'] >= 90 ? 'bg-amber-500' : 'bg-indigo-500' }}"
                         style="width: {{ min(100, $metrics['usage_ratio']) }}%"></div>
                </div>
                <p class="mt-2 text-xs {{ $metrics['usage_ratio'] >= 90 ? 'text-amber-400' : 'text-slate-500' }}">
                    {{ $metrics['clients_remaining'] }} disponibles · portafolio de cada conjunto ilimitado
                </p>
            </div>
        </div>

        @if ($metrics['billing_cycle'] !== 'annual')
            <div class="rounded-2xl border border-emerald-800/40 bg-emerald-950/20 p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-emerald-300">Pasa a licencia anual</p>
                    <p class="text-sm text-slate-400 mt-1">
                        Con tu cupo actual pagarías {{ $fmt($annualForCurrent->priceAnnual) }}/año
                        y ahorrarías {{ $fmt($annualForCurrent->annualSavings) }} frente a 12 mensualidades.
                    </p>
                </div>
                <p class="text-xs text-slate-500 shrink-0">Solicita el cambio a plataforma</p>
            </div>
        @endif

        @if (count($upgradeQuotes) > 0)
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wide">Ampliar cupo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($upgradeQuotes as $upgrade)
                        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                            <p class="font-semibold text-white">{{ $upgrade['label'] }}</p>
                            <p class="mt-2 text-sm text-slate-400">
                                Mensual {{ $fmt($upgrade['monthly']->priceMonthly) }}
                                · Anual {{ $fmt($upgrade['annual']->priceAnnual) }}
                                <span class="text-emerald-400">(−{{ $fmt($upgrade['annual']->annualSavings) }})</span>
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $fmt($upgrade['monthly']->effectiveUnitMonthly) }}/cliente · desc. volumen {{ $upgrade['monthly']->volumeDiscountLabel() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Total clientes</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $metrics['total'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Activos</p>
                <p class="mt-2 text-3xl font-bold text-emerald-400">{{ $metrics['active'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Inactivos</p>
                <p class="mt-2 text-3xl font-bold text-slate-400">{{ $metrics['inactive'] }}</p>
            </div>
        </div>

        @if (! empty($metrics['features']))
            <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Incluido en tu modalidad</p>
                <ul class="flex flex-wrap gap-2">
                    @foreach ($metrics['features'] as $feature)
                        <li class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">{{ str_replace('_', ' ', $feature) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="font-semibold text-white">Clientes recientes</h3>
                @can('company.clients.manage')
                    @if (! $metrics['is_quota_full'])
                        <a href="{{ route('company.clients.create') }}" class="text-sm text-indigo-400 hover:text-indigo-300">+ Nuevo cliente</a>
                    @else
                        <span class="text-sm text-amber-400">Cupo lleno</span>
                    @endif
                @endcan
            </div>
            <ul class="divide-y divide-slate-800">
                @forelse ($recentClients as $client)
                    <li class="px-5 py-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-white">{{ $client->name }}</p>
                            <p class="text-xs text-slate-500">Login: usuario{{ $client->loginDomain() }}</p>
                        </div>
                        <a href="{{ route('company.clients.show', $client) }}" class="text-sm text-indigo-400 hover:text-indigo-300">Ver</a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-slate-500 text-sm">Aún no hay clientes. Crea el primero desde el menú Clientes.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-company-layout>
