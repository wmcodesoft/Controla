<x-access-layout title="Histórico de Registros">
    <div class="rounded-xl bg-gradient-to-r from-slate-800 to-indigo-900 p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-300">Control de Acceso</p>
            <h2 class="text-xl font-bold text-white">Histórico de Registros</h2>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-slate-900 rounded-xl border border-slate-800 p-1 w-fit">
        <a href="{{ route('access.logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-400 hover:text-white hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Registros del día
        </a>
        <a href="{{ route('access.logs.active') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-400 hover:text-white hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Activos
        </a>
        <a href="{{ route('access.logs.historical') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-indigo-600 text-white">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Histórico
        </a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('access.logs.historical') }}" class="bg-slate-900 rounded-xl border border-slate-800 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Fecha desde</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg bg-slate-950 border-slate-700 text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Fecha hasta</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg bg-slate-950 border-slate-700 text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Tipo de persona</label>
                <select name="person_type" class="w-full rounded-lg bg-slate-950 border-slate-700 text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="resident" {{ request('person_type') === 'resident' ? 'selected' : '' }}>Persona</option>
                    <option value="visitor" {{ request('person_type') === 'visitor' ? 'selected' : '' }}>Visitante</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Estado</label>
                <select name="status" class="w-full rounded-lg bg-slate-950 border-slate-700 text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Dentro</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Salió</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o documento..." class="w-full rounded-lg bg-slate-950 border-slate-700 text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div class="flex items-center gap-2 mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filtrar
            </button>
            <a href="{{ route('access.logs.historical') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-700 transition-colors">
                Limpiar
            </a>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-white">Registros</h3>
                <p class="text-sm text-slate-500 mt-0.5">{{ $logs->total() }} registros encontrados</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Persona</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Documento</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Destino</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ingreso</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Salida</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($logs as $log)
                    @php
                        $personName = $log->visitor?->full_name ?? $log->resident?->full_name ?? '-';
                        $personDoc = $log->visitor ? $log->visitor->document_type . ' ' . $log->visitor->document_number : ($log->resident ? $log->resident->document_type . ' ' . $log->resident->document_number : '-');
                        if ($log->resident_id) {
                            $typeLabel = str_contains($log->access_type, 'vehicle') ? 'Persona Vehicular' : 'Persona';
                            $typeClass = 'bg-teal-900/30 text-teal-300 ring-teal-700';
                        } else {
                            $typeLabel = str_contains($log->access_type, 'vehicle') ? 'Visit. Vehicular' : 'Visitante';
                            $typeClass = str_contains($log->access_type, 'vehicle') ? 'bg-cyan-900/30 text-cyan-300 ring-cyan-700' : 'bg-blue-900/30 text-blue-300 ring-blue-700';
                        }
                        $destination = $log->structure?->full_path ?? $log->host?->name ?? '-';
                    @endphp
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($personName, 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-white">{{ $personName }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {{ $typeClass }}">
                                {{ $typeLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $personDoc }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $destination }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->location->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->entry_time->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->entry_time->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->exit_time?->format('H:i') ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->status == 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-900/30 text-emerald-300 ring-1 ring-emerald-700">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                    Dentro
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 ring-1 ring-slate-700">
                                    <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                    Salió
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="mt-2 text-sm text-slate-500">No se encontraron registros</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
</x-access-layout>
