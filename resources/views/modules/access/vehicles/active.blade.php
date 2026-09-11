<x-access-layout title="Vehículos Dentro">
    <div class=" bg-gradient-to-r from-slate-800 to-indigo-900 p-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-300">Vehículos</p>
            <h2 class="text-xl font-bold text-white">Vehículos Dentro</h2>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mt-6 mb-6 bg-slate-900 rounded-xl border border-slate-800 p-1 w-fit">
        <a href="{{ route('access.vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-400 hover:text-white hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Control
        </a>
        <a href="{{ route('access.vehicles.active') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-indigo-600 text-white">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Vehículos Dentro
        </a>
        <a href="{{ route('access.vehicles.list') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-400 hover:text-white hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Vehículos Registrados
        </a>
    </div>

    @if($activeLogs->count())
    <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-white">Vehículos Actualmente Dentro</h3>
                <p class="text-sm text-slate-500 mt-0.5">Vehículos con ingreso registrado activo</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-900/30 text-emerald-300 ring-1 ring-emerald-700">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                {{ $activeLogs->count() }} dentro
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Placa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Persona</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Vehículo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ingreso</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @foreach($activeLogs as $log)
                    @php
                        $personName = $log->resident?->full_name ?? $log->visitor?->full_name ?? '-';
                        $isResident = $log->resident_id !== null;
                    @endphp
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-cyan-900/30 text-cyan-300 ring-1 ring-cyan-700 uppercase">{{ $log->vehicle?->plate ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase {{ $isResident ? 'bg-teal-900/30 text-teal-300' : 'bg-emerald-900/30 text-emerald-300' }}">
                                {{ $isResident ? 'Persona' : 'Visitante' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $personName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->vehicle?->brand }} {{ $log->vehicle?->model }} <span class="text-slate-500">({{ $log->vehicle?->color }})</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->location?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->entry_time->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <form action="{{ route('access.logs.exit', $log) }}" method="POST" onsubmit="return confirm('¿Registrar salida?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-red-700 transition-colors shadow-sm">Salida</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-slate-900 rounded-xl border border-slate-800 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0V21h14v-5m0 0h3l2-4-2-4h-3l-2 4h-1"/></svg>
        <p class="mt-3 text-sm text-slate-400">No hay vehículos dentro del conjunto</p>
    </div>
    @endif
</x-access-layout>
