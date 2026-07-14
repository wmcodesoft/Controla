<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes e Informes</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('modules.access.partials.subnav')

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Total Ingresos</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalEntries }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Dentro</p>
                    <p class="mt-1 text-2xl font-semibold text-green-600">{{ $activeEntries }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Hoy</p>
                    <p class="mt-1 text-2xl font-semibold text-blue-600">{{ $todayEntries }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Visitantes</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalVisitors }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">Prom. Estadía</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $avgDuration ? round($avgDuration) . ' min' : '-' }}</p>
                </div>
            </div>

            <div class="mt-6 bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
                    <a href="{{ route('access.reports.export') }}?{{ http_build_query(request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Exportar Excel
                    </a>
                </div>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Desde</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hasta</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completados</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="access_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            <option value="visitor" {{ request('access_type') == 'visitor' ? 'selected' : '' }}>Visitante</option>
                            <option value="visitor_vehicle" {{ request('access_type') == 'visitor_vehicle' ? 'selected' : '' }}>Visit. Vehicular</option>
                            <option value="resident" {{ request('access_type') == 'resident' ? 'selected' : '' }}>Residente</option>
                            <option value="resident_vehicle" {{ request('access_type') == 'resident_vehicle' ? 'selected' : '' }}>Resid. Vehicular</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                        <select name="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Filtrar</button>
                    </div>
                </form>
            </div>

            <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Registros de Acceso</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persona</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anfitrión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingreso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salida</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        @php
                            $person = $log->visitor ?? $log->resident;
                            $personName = $person?->full_name ?? '-';
                            $personDoc = $person && $person->document_type ? $person->document_type . ' ' . $person->document_number : '-';
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $personName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $personDoc }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->access_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->host?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->entry_time->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->exit_time?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $log->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $log->status == 'active' ? 'Dentro' : 'Salió' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Sin resultados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
