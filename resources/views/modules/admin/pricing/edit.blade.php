@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
@endphp

<x-admin-layout title="Tabla de precios">
    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-violet-400/80">Comercial · Plataforma</p>
                <h2 class="mt-2 text-3xl font-bold text-white tracking-tight">Tabla de precios</h2>
                <p class="mt-2 text-sm text-slate-400 max-w-2xl">
                    Define los dos precios unitarios (por cliente / mes). El resto de la matriz se calcula solo:
                    descuento por volumen (1 → 100) y descuento anual (~{{ number_format($annualDiscount * 100, 0) }}%).
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.pricing.edit', ['cycle' => 'monthly']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium {{ $cycle->value === 'monthly' ? 'bg-violet-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Mensual
                </a>
                <a href="{{ route('admin.pricing.edit', ['cycle' => 'annual']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium {{ $cycle->value === 'annual' ? 'bg-violet-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    Anual
                    <span class="ml-1 text-xs opacity-80">recomendado</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-700/60 bg-emerald-950/40 px-4 py-3 text-sm text-emerald-200">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <section class="xl:col-span-4 rounded-2xl border border-slate-800 bg-gradient-to-b from-slate-900 to-slate-950 p-6 shadow-xl shadow-black/20">
                <h3 class="text-lg font-semibold text-white">Precios base (super admin)</h3>
                <p class="mt-1 text-sm text-slate-400">Única entrada manual. Competencia comercial tuya.</p>

                @can('platform.companies.manage')
                    <form method="POST" action="{{ route('admin.pricing.update') }}" class="mt-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-slate-300">Unitario · Sin hardware</label>
                            <div class="mt-1 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">$</span>
                                <input type="number" step="1000" min="1000" name="unit_price_manual"
                                       value="{{ old('unit_price_manual', (int) $settings->unit_price_manual) }}"
                                       class="w-full rounded-xl border-slate-700 bg-slate-950 pl-7 text-white focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">COP por cliente / mes · modalidad manual</p>
                            @error('unit_price_manual')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300">Unitario · Con hardware</label>
                            <div class="mt-1 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">$</span>
                                <input type="number" step="1000" min="1000" name="unit_price_hardware"
                                       value="{{ old('unit_price_hardware', (int) $settings->unit_price_hardware) }}"
                                       class="w-full rounded-xl border-slate-700 bg-slate-950 pl-7 text-white focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">COP por cliente / mes · lectores, LPR, facial…</p>
                            @error('unit_price_hardware')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-500 transition">
                            Guardar y recalcular matriz
                        </button>
                    </form>
                @else
                    <div class="mt-6 space-y-3 text-sm">
                        <p class="text-slate-300">Manual: <span class="font-semibold text-white">{{ $fmt((float) $settings->unit_price_manual) }}</span></p>
                        <p class="text-slate-300">Hardware: <span class="font-semibold text-white">{{ $fmt((float) $settings->unit_price_hardware) }}</span></p>
                    </div>
                @endcan

                <div class="mt-6 rounded-xl border border-slate-800 bg-slate-950/60 p-4 text-xs text-slate-400 space-y-1">
                    <p>Volumen: 1 (0%) · 5 (10%) · 10 (15%) · 50 (25%) · 100 (30%)</p>
                    <p>Anual: −{{ number_format($annualDiscount * 100, 0) }}% sobre 12 meses del paquete</p>
                    <p>Moneda: {{ $settings->currency }}</p>
                </div>
            </section>

            <section class="xl:col-span-8 rounded-2xl border border-slate-800 bg-slate-900/80 overflow-hidden shadow-xl shadow-black/20">
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-white">Matriz calculada · {{ $cycle->label() }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Precios de paquete (no editables celda a celda)</p>
                    </div>
                    @if ($cycle === \App\Enums\BillingCycle::Annual)
                        <span class="rounded-full bg-emerald-900/50 border border-emerald-700/50 px-3 py-1 text-xs text-emerald-300">
                            Ahorro vs 12 mensualidades
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-950/80 text-slate-500 uppercase text-xs tracking-wide">
                            <tr>
                                <th class="px-5 py-3 text-left">Cupo</th>
                                <th class="px-5 py-3 text-left">Desc. vol.</th>
                                <th class="px-5 py-3 text-right">Sin hardware</th>
                                <th class="px-5 py-3 text-right">Con hardware</th>
                                <th class="px-5 py-3 text-right">$/cliente eff.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach ($matrix as $row)
                                @php
                                    $cell = $cycle->value === 'annual' ? 'price_annual' : 'price_monthly';
                                    $period = $cycle->value === 'annual' ? '/año' : '/mes';
                                @endphp
                                <tr class="hover:bg-slate-800/40">
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-white">{{ $row['size'] }}</span>
                                        <span class="text-slate-500">{{ $row['size'] === 1 ? 'cliente' : 'clientes' }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-300">−{{ number_format($row['volume_discount_pct'] * 100, 0) }}%</td>
                                    <td class="px-5 py-4 text-right">
                                        <p class="font-semibold text-white">{{ $fmt((float) $row['manual'][$cell]) }}{{ $period }}</p>
                                        @if ($cycle->value === 'annual')
                                            <p class="text-xs text-emerald-400/90">Ahorras {{ $fmt((float) $row['manual']['annual_savings']) }}</p>
                                        @else
                                            <p class="text-xs text-slate-500">Lista {{ $fmt((float) $row['manual']['list_monthly_without_volume']) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <p class="font-semibold text-violet-300">{{ $fmt((float) $row['hardware'][$cell]) }}{{ $period }}</p>
                                        @if ($cycle->value === 'annual')
                                            <p class="text-xs text-emerald-400/90">Ahorras {{ $fmt((float) $row['hardware']['annual_savings']) }}</p>
                                        @else
                                            <p class="text-xs text-slate-500">Lista {{ $fmt((float) $row['hardware']['list_monthly_without_volume']) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right text-slate-400">
                                        <p>{{ $fmt((float) $row['manual']['effective_unit_monthly']) }}</p>
                                        <p class="text-violet-400/80">{{ $fmt((float) $row['hardware']['effective_unit_monthly']) }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>
