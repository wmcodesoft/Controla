@php
    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');
@endphp

<x-admin-layout title="Resumen plataforma">
    <div class="space-y-8">
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-gradient-to-br from-violet-950/40 via-slate-900 to-slate-950 p-8">
            <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-violet-600/20 blur-3xl"></div>
            <div class="relative">
                <p class="text-xs uppercase tracking-[0.2em] text-violet-300/80">Súper Admin</p>
                <h2 class="mt-2 text-3xl font-bold text-white">Panel de plataforma</h2>
                <p class="mt-2 text-sm text-slate-400 max-w-xl">
                    Vista global del SaaS: empresas, cupos y anclas comerciales. Ajusta tarifas en la tabla de precios.
                </p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('admin.pricing.edit') }}" class="rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-violet-500">
                        Tabla de precios
                    </a>
                    <a href="{{ route('admin.companies.index') }}" class="rounded-xl border border-slate-700 px-4 py-2.5 text-sm font-medium text-slate-200 hover:bg-slate-800">
                        Gestionar empresas
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Empresas</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $metrics['companies_total'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Activas</p>
                <p class="mt-2 text-3xl font-bold text-violet-400">{{ $metrics['companies_active'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Clientes</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $metrics['clients_total'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Clientes activos</p>
                <p class="mt-2 text-3xl font-bold text-emerald-400">{{ $metrics['clients_active'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Usuarios</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $metrics['users_total'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-violet-800/40 bg-violet-950/20 p-6 lg:col-span-1">
                <p class="text-xs uppercase tracking-wide text-violet-300/80">Anclas comerciales</p>
                <p class="mt-3 text-sm text-slate-400">Unitario manual</p>
                <p class="text-2xl font-bold text-white">{{ $fmt((float) $pricing->unit_price_manual) }}<span class="text-sm font-normal text-slate-500">/mes</span></p>
                <p class="mt-4 text-sm text-slate-400">Unitario hardware</p>
                <p class="text-2xl font-bold text-violet-300">{{ $fmt((float) $pricing->unit_price_hardware) }}<span class="text-sm font-normal text-slate-500">/mes</span></p>
                <a href="{{ route('admin.pricing.edit') }}" class="mt-5 inline-block text-sm text-violet-400 hover:text-violet-300">Editar precios →</a>
            </div>

            <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden lg:col-span-2">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Empresas de seguridad</h3>
                    <a href="{{ route('admin.companies.index') }}" class="text-sm text-violet-400 hover:text-violet-300">Ver todas</a>
                </div>
                <ul class="divide-y divide-slate-800">
                    @forelse ($recentCompanies as $company)
                        <li class="px-5 py-4 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-medium text-white">{{ $company->trade_name ?? $company->legal_name }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $company->clients_count }} / {{ $company->max_clients }} clientes
                                    · {{ $company->packageLabel() }}
                                    · {{ $company->billingPeriodLabel() }}
                                </p>
                            </div>
                            <a href="{{ route('admin.companies.show', $company) }}" class="text-sm text-violet-400 hover:text-violet-300 shrink-0">Paquete</a>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-slate-500 text-sm">No hay empresas registradas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-admin-layout>
