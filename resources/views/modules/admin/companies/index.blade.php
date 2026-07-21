@php
    use App\Support\Tenancy\CompanySubscriptionState;

    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
@endphp

<x-admin-layout title="Empresas">
    <div class="flex flex-col flex-1 min-h-0 gap-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shrink-0">
            <div>
                <p class="text-xs text-slate-500">Plataforma · Cartera comercial</p>
                <h3 class="text-sm font-semibold text-white mt-0.5">Empresas de seguridad</h3>
                <p class="text-xs text-slate-500 mt-1">Cupo operativo, modalidad y ciclo de facturación.</p>
            </div>
            <x-ui.button variant="platform" :href="route('admin.pricing.edit')">Tabla de precios</x-ui.button>
        </div>

        <div class="flex-1 min-h-0 rounded-lg border border-slate-800 bg-slate-900/80 overflow-hidden flex flex-col">
            <div class="flex-1 overflow-y-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium">Empresa</th>
                            <th class="px-4 py-2 text-left font-medium">Paquete</th>
                            <th class="px-4 py-2 text-left font-medium">Ciclo</th>
                            <th class="px-4 py-2 text-left font-medium">Cupo</th>
                            <th class="px-4 py-2 text-left font-medium">Contratado</th>
                            <th class="px-4 py-2 text-left font-medium">Estado</th>
                            <th class="px-4 py-2 text-right font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse ($companies as $company)
                            @php $bucket = CompanySubscriptionState::bucket($company); @endphp
                            <tr class="hover:bg-slate-800/30">
                                <td class="px-4 py-2.5">
                                    <p class="font-medium text-slate-200">{{ $company->trade_name }}</p>
                                    <p class="text-xs text-slate-600">{{ $company->tax_id }}</p>
                                </td>
                                <td class="px-4 py-2.5 text-slate-300">{{ $company->packageLabel() }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs {{ $company->billing_cycle?->value === 'annual' ? 'bg-emerald-900/30 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                                        {{ $company->billingPeriodLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-slate-300 tabular-nums">
                                    {{ $company->operational_clients_count ?? $company->clients_count }} / {{ $company->max_clients }}
                                </td>
                                <td class="px-4 py-2.5 text-slate-300 tabular-nums">
                                    {{ $fmt($company->contractedAmount()) }}
                                    <span class="text-slate-600 text-xs">{{ $company->billing_cycle?->value === 'annual' ? '/año' : '/mes' }}</span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs
                                        @if($bucket->value === 'current') bg-emerald-900/30 text-emerald-300
                                        @elseif($bucket->value === 'due_soon') bg-amber-900/30 text-amber-300
                                        @elseif($bucket->value === 'overdue') bg-red-900/30 text-red-300
                                        @else bg-slate-800 text-slate-400 @endif">
                                        {{ $bucket->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="text-xs text-violet-400 hover:text-violet-300 font-medium">Gestionar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500">No hay empresas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($companies->hasPages())
            <div class="shrink-0">{{ $companies->links() }}</div>
        @endif
    </div>
</x-admin-layout>
