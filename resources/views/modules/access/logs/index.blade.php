<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Control de Acceso</p>
                    <h2 class="text-xl font-bold text-white">Ingreso / Salida</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('access.logs.entry') }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Ingreso
                    </a>
                    @if($activeLogs->count())
                    <form action="{{ route('access.logs.bulk-exit') }}" method="POST" onsubmit="return confirm('¿Marcar salida de todos los registros activos?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-400 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Salida Masiva
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="-mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('modules.access.partials.subnav')

            @if(session('success'))
            <div class="mt-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @if($activeLogs->count())
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Personas Dentro del Edificio</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Registros activos con ingreso registrado</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                        {{ $activeLogs->count() }} dentro
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Persona</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Destino</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ingreso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tiempo</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($activeLogs as $log)
                            @php
                                $personName = $log->visitor?->full_name ?? $log->resident?->full_name ?? '-';
                                $personDoc = $log->visitor ? $log->visitor->document_type . ' ' . $log->visitor->document_number : ($log->resident ? $log->resident->document_type . ' ' . $log->resident->document_number : '-');
                                $personType = $log->access_type == 'visitor_vehicle' ? 'Visit. Vehicular' : 'Visitante';
                                $hoursInside = $log->entry_time->diffInHours(now());
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $hoursInside > 12 ? 'bg-red-50 hover:bg-red-100' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full {{ $hoursInside > 12 ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-indigo-500 to-indigo-700' }} flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($personName, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $personName }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {{ $personType == 'Visit. Vehicular' ? 'bg-cyan-50 text-cyan-700 ring-cyan-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20' }}">
                                        {{ $personType }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $personDoc }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->housingUnit?->full_label ?? $log->host?->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->location->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->entry_time->format('H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        <span class="text-sm {{ $hoursInside > 12 ? 'text-red-700 font-semibold' : 'text-gray-600' }}">
                                            {{ $log->entry_time->diffForHumans(now(), true) }}
                                        </span>
                                        @if($hoursInside > 12)
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <form action="{{ route('access.logs.exit', $log) }}" method="POST" onsubmit="return confirm('¿Registrar salida de {{ $personName }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-red-700 transition-colors shadow-sm">
                                            Registrar Salida
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Registros del Día</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Todos los movimientos registrados hoy</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Persona</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ingreso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salida</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($todayLogs as $log)
                            @php
                                $personName = $log->visitor?->full_name ?? $log->resident?->full_name ?? '-';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($personName, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $personName }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {{ $log->access_type == 'visitor_vehicle' ? 'bg-cyan-50 text-cyan-700 ring-cyan-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20' }}">
                                        {{ $log->access_type == 'visitor_vehicle' ? 'Visit. Vehicular' : 'Visitante' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->entry_time->format('H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->exit_time?->format('H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->status == 'active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Dentro
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-600 ring-1 ring-gray-500/20">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                            Salió
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6"/></svg>
                                    <p class="mt-2 text-sm text-gray-500">Sin registros hoy</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $todayLogs->links() }}</div>
        </div>
    </div>
</x-app-layout>