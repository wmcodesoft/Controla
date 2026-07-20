@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
@endphp

<x-admin-layout :title="'Empresa: '.$company->trade_name">
    <div class="max-w-4xl space-y-6">
        <div>
            <a href="{{ route('admin.companies.index') }}" class="text-sm text-slate-400 hover:text-white">&larr; Empresas</a>
            <h2 class="mt-2 text-3xl font-bold text-white">{{ $company->trade_name }}</h2>
            <p class="text-sm text-slate-400 mt-1">{{ $company->legal_name }} · NIT {{ $company->tax_id }}</p>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-800 bg-emerald-950/40 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase text-slate-500">Cupo</p>
                <p class="mt-2 text-2xl font-bold text-white">{{ $company->clients_count }} / {{ $company->max_clients }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase text-slate-500">Modalidad</p>
                <p class="mt-2 text-lg font-semibold text-white">{{ $company->package_modality?->label() ?? '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase text-slate-500">Ciclo</p>
                <p class="mt-2 text-lg font-semibold {{ $company->billing_cycle?->value === 'annual' ? 'text-emerald-400' : 'text-white' }}">
                    {{ $company->billingPeriodLabel() }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase text-slate-500">Contratado</p>
                <p class="mt-2 text-2xl font-bold text-white">{{ $fmt($company->contractedAmount()) }}</p>
                <p class="text-xs text-slate-500">{{ $company->billing_cycle?->value === 'annual' ? 'por año' : 'por mes' }}</p>
            </div>
        </div>

        @if ($company->package_ends_at)
            <div class="rounded-xl border border-slate-800 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                Vigencia:
                {{ $company->package_starts_at?->format('d/m/Y') ?? '—' }}
                →
                {{ $company->package_ends_at->format('d/m/Y') }}
                · {{ $company->subscription_status?->label() ?? 'Activa' }}
            </div>
        @endif

        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 space-y-5">
            <div>
                <h3 class="font-semibold text-white text-lg">Asignar / cambiar paquete</h3>
                <p class="text-sm text-slate-400 mt-1">
                    El precio se calcula con la tabla vigente (unitarios del super admin + descuentos).
                    Al guardar se congela el snapshot en el contrato de la empresa.
                </p>
            </div>

            @can('platform.companies.manage')
                <form method="POST" action="{{ route('admin.companies.package.update', $company) }}" class="space-y-5" id="package-form">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300">Paquete (cupo × modalidad)</label>
                            <select name="package_sku" required class="mt-1 w-full rounded-xl border-slate-700 bg-slate-950 text-white">
                                @foreach ($packageOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('package_sku', $company->package_sku?->value) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('package_sku')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300">Ciclo de facturación</label>
                            <select name="billing_cycle" required class="mt-1 w-full rounded-xl border-slate-700 bg-slate-950 text-white">
                                @foreach ($cycleOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('billing_cycle', $company->billing_cycle?->value ?? 'monthly') === $value)>
                                        {{ $label }}{{ $value === 'annual' ? ' · recomendado' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('billing_cycle')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-violet-800/40 bg-violet-950/20 p-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500 uppercase">Mensual (ref.)</p>
                            <p class="text-lg font-bold text-white">{{ $fmt($quote->priceMonthly) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-400/80 uppercase">Anual (recomendado)</p>
                            <p class="text-lg font-bold text-emerald-300">{{ $fmt($quoteAnnual->priceAnnual) }}</p>
                            <p class="text-xs text-emerald-400/70">Ahorras {{ $fmt($quoteAnnual->annualSavings) }} vs 12 meses</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase">Desc. volumen</p>
                            <p class="text-lg font-bold text-white">−{{ $quote->volumeDiscountLabel() }}</p>
                            <p class="text-xs text-slate-500">{{ $fmt($quote->effectiveUnitMonthly) }}/cliente eff.</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">
                        Vista previa con el SKU/ciclo seleccionados al cargar. Al cambiar el select, guarda para aplicar el precio exacto al contrato.
                    </p>

                    <button type="submit" class="rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-violet-500">
                        Guardar paquete y ciclo
                    </button>
                </form>
            @else
                <p class="text-sm text-slate-500">No tienes permiso para cambiar el paquete.</p>
            @endcan
        </div>

        @if ($company->package_modality)
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                <h3 class="font-semibold text-white mb-3">Capacidades incluidas</h3>
                <ul class="flex flex-wrap gap-2">
                    @foreach ($company->package_modality->features() as $feature)
                        <li class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">{{ str_replace('_', ' ', $feature) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-admin-layout>
