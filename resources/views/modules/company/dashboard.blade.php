@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
    $endsAt = $metrics['package_ends_at'] ?? null;
    $daysLeft = $metrics['days_until_renewal'];
    $usagePct = min(100, $metrics['usage_ratio']);
    $isAnnual = $metrics['billing_cycle'] === 'annual';
    $statusColor = $metrics['is_expired']
        ? 'text-red-400'
        : ($metrics['is_renewal_soon'] ? 'text-amber-400' : 'text-emerald-400');
    $firstUpgrade = $upgradeQuotes[0] ?? null;
@endphp

<x-company-layout title="Resumen empresa">
    <div class="space-y-4">
        <section class="rounded-lg border border-slate-800 bg-slate-900/60 px-4 py-3">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                    <span class="inline-flex items-center gap-1.5 font-medium {{ $statusColor }}">
                        <span class="text-[10px] leading-none">●</span>
                        {{ $metrics['subscription_status_label'] }}
                    </span>
                    <span>{{ $metrics['package_label'] }}</span>
                    <span class="hidden sm:inline text-slate-600">·</span>
                    <span>{{ $metrics['billing_cycle_label'] }} · {{ $fmt($metrics['contracted_amount']) }}</span>
                </div>
                @if ($endsAt)
                    <p class="text-xs text-slate-500 shrink-0">
                        Renueva {{ $endsAt->format('d M Y') }}
                        @if ($daysLeft !== null && $daysLeft >= 0)
                            <span class="{{ $daysLeft <= 30 ? 'text-amber-400' : '' }}">({{ $daysLeft }} días)</span>
                        @endif
                    </p>
                @endif
            </div>
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs mb-1">
                    <span class="text-slate-500">Cupo de conjuntos</span>
                    <span class="text-slate-400">
                        {{ $metrics['total'] }} / {{ $metrics['max_clients'] }}
                        <span class="text-slate-600">· {{ $metrics['clients_remaining'] }} disponibles</span>
                    </span>
                </div>
                <div class="h-1.5 rounded-full bg-slate-800 overflow-hidden">
                    <div class="h-full rounded-full {{ $usagePct >= 90 ? 'bg-amber-500' : 'bg-indigo-500' }}"
                         style="width: {{ max($usagePct, $metrics['total'] > 0 ? 2 : 0) }}%"></div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <section class="lg:col-span-8 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-sm font-semibold text-white">Conjuntos</h3>
                    @can('company.clients.view')
                        <a href="{{ route('company.clients.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300">
                            Ver todos
                        </a>
                    @endcan
                </div>

                <div class="rounded-lg border border-slate-800 overflow-hidden bg-slate-900/80">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-medium">Conjunto</th>
                                    <th class="px-4 py-2.5 text-left font-medium">Login APP</th>
                                    <th class="px-4 py-2.5 text-right font-medium">Usuarios</th>
                                    <th class="px-4 py-2.5 text-left font-medium">Estado</th>
                                    <th class="px-4 py-2.5 text-right font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse ($recentClients as $client)
                                    <tr class="hover:bg-slate-800/30">
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-slate-200">{{ $client->name }}</p>
                                            <p class="text-xs text-slate-600">{{ $client->slug }}</p>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs text-indigo-300/90">usuario{{ $client->loginDomain() }}</td>
                                        <td class="px-4 py-3 text-right text-slate-400">{{ $client->assignments_count }}</td>
                                        <td class="px-4 py-3">
                                            @if ($client->is_active)
                                                <span class="inline-flex rounded-full bg-emerald-900/30 px-2 py-0.5 text-xs text-emerald-300">Activo</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-800 px-2 py-0.5 text-xs text-slate-500">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                                            <a href="{{ route('company.clients.show', $client) }}" class="text-xs text-indigo-400 hover:text-indigo-300">Ver</a>
                                            @can('operate', $client)
                                                <form action="{{ route('company.clients.activate', $client) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-emerald-400 hover:text-emerald-300">Operar</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                            Sin conjuntos registrados.
                                            @can('company.clients.manage')
                                                @if (! $metrics['is_quota_full'])
                                                    <a href="{{ route('company.clients.create') }}" class="block mt-2 text-indigo-400 hover:text-indigo-300">Crear primer conjunto</a>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <aside class="lg:col-span-4">
                <div class="rounded-lg border border-slate-800 bg-slate-900/80 divide-y divide-slate-800">
                    <div class="px-4 py-3">
                        <p class="text-xs font-medium text-slate-300">Cuenta</p>
                    </div>

                    <div class="px-4 py-3 space-y-1">
                        <p class="text-xs text-slate-500">Próximo cargo</p>
                        <p class="text-base font-semibold text-white tabular-nums">{{ $fmt($metrics['contracted_amount']) }}</p>
                        @if ($endsAt)
                            <p class="text-xs text-slate-500">
                                {{ $endsAt->format('d M Y') }} · facturación {{ $isAnnual ? 'anual' : 'mensual' }}
                            </p>
                        @else
                            <p class="text-xs text-slate-500">Facturación {{ $isAnnual ? 'anual' : 'mensual' }}</p>
                        @endif
                    </div>

                    @if (! $isAnnual)
                        <div class="px-4 py-3 space-y-2 bg-emerald-950/25">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-medium text-emerald-200">Licencia anual</p>
                                <span class="shrink-0 rounded-full bg-emerald-500/20 px-1.5 py-0.5 text-[10px] font-medium text-emerald-300">Recomendado</span>
                            </div>
                            <p class="text-xs leading-relaxed">
                                <span class="font-medium text-emerald-100">{{ $fmt($annualForCurrent->priceAnnual) }}/año</span>
                                <span class="text-slate-500"> en lugar de </span>
                                <span class="text-slate-500 line-through">{{ $fmt($monthlyForCurrent->priceMonthly * 12) }}</span>
                            </p>
                            <p class="text-xs font-medium text-emerald-400">
                                Ahorro {{ $fmt($annualForCurrent->annualSavings) }} ({{ $annualForCurrent->annualDiscountLabel() }})
                            </p>
                            <x-ui.button variant="success" size="sm"
                                href="mailto:admin@control-acceso.test?subject=Solicitud%20licencia%20anual%20-%20{{ urlencode($metrics['company_name']) }}">
                                Solicitar cambio
                            </x-ui.button>
                        </div>
                    @endif

                    @if ($firstUpgrade)
                        <div class="px-4 py-3 space-y-2 bg-indigo-950/20">
                            <p class="text-xs font-medium text-indigo-200">Ampliar cupo</p>
                            <div class="rounded-lg border border-indigo-500/30 bg-indigo-950/40 px-3 py-2.5">
                                <p class="text-xs font-medium text-white">{{ $firstUpgrade['label'] }}</p>
                                <p class="mt-1 text-xs">
                                    <span class="text-slate-400">Mensual {{ $fmt($firstUpgrade['monthly']->priceMonthly) }}</span>
                                    <span class="text-slate-600"> · </span>
                                    <span class="text-indigo-300">Anual {{ $fmt($firstUpgrade['annual']->priceAnnual) }}</span>
                                </p>
                                <p class="mt-0.5 text-xs text-indigo-300/70">
                                    {{ $fmt($firstUpgrade['monthly']->effectiveUnitMonthly) }}/cliente
                                    · desc. volumen {{ $firstUpgrade['monthly']->volumeDiscountLabel() }}
                                </p>
                            </div>
                            <x-ui.button size="sm"
                                href="mailto:admin@control-acceso.test?subject=Ampliar%20cupo%20-%20{{ urlencode($metrics['company_name']) }}">
                                Solicitar ampliación
                            </x-ui.button>
                        </div>
                    @endif

                    @if (! empty($metrics['feature_labels']))
                        <div class="px-4 py-3 space-y-2">
                            <p class="text-xs text-indigo-300/80">Incluido en tu plan</p>
                            <ul class="flex flex-wrap gap-1.5">
                                @foreach ($metrics['feature_labels'] as $label)
                                    <li class="rounded-md border border-indigo-800/50 bg-indigo-900/30 px-2 py-0.5 text-xs text-indigo-200">{{ $label }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-company-layout>
