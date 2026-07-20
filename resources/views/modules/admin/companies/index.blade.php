@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
@endphp

<x-admin-layout title="Empresas">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-violet-400/80">Plataforma</p>
                <h2 class="mt-1 text-3xl font-bold text-white">Empresas de seguridad</h2>
                <p class="text-sm text-slate-400 mt-1">Asigna cupo, modalidad y ciclo de facturación (mensual / anual).</p>
            </div>
            <a href="{{ route('admin.pricing.edit') }}" class="text-sm text-violet-400 hover:text-violet-300">Ver tabla de precios →</a>
        </div>

        <div class="rounded-2xl border border-slate-800 overflow-hidden bg-slate-900/90 shadow-xl shadow-black/10">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Empresa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Paquete</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Ciclo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Cupo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Contratado</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($companies as $company)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-4">
                                <p class="font-medium text-white">{{ $company->trade_name }}</p>
                                <p class="text-xs text-slate-500">{{ $company->tax_id }}</p>
                            </td>
                            <td class="px-4 py-4 text-slate-300">{{ $company->packageLabel() }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-2.5 py-0.5 text-xs {{ $company->billing_cycle?->value === 'annual' ? 'bg-emerald-900/40 text-emerald-300' : 'bg-slate-800 text-slate-300' }}">
                                    {{ $company->billingPeriodLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-300">{{ $company->clients_count }} / {{ $company->max_clients }}</td>
                            <td class="px-4 py-4 text-slate-300">
                                {{ $fmt($company->contractedAmount()) }}
                                <span class="text-slate-500 text-xs">{{ $company->billing_cycle?->value === 'annual' ? '/año' : '/mes' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @if ($company->is_active)
                                    <span class="text-xs text-emerald-400">Activa</span>
                                @else
                                    <span class="text-xs text-slate-500">Inactiva</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.companies.show', $company) }}" class="text-violet-400 hover:text-violet-300 font-medium">Gestionar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">No hay empresas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $companies->links() }}</div>
    </div>
</x-admin-layout>
