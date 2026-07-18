<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Módulo de Acceso</p>
                    <h2 class="text-xl font-bold text-white">Centro de Operaciones</h2>
                </div>
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-400/20 text-emerald-300 ring-1 ring-emerald-400/30">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5"></span>
                    Sistema Activo
                </span>
            </div>
        </div>
    </x-slot>

    <div class="-mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('modules.access.partials.subnav')

            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-bl-3xl -mr-4 -mt-4"></div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dentro</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $activeEntries }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-3xl -mr-4 -mt-4"></div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hoy</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $todayEntries }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-amber-50 rounded-bl-3xl -mr-4 -mt-4"></div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Correspondencia</p>
                    <p class="mt-1 text-2xl font-bold text-amber-600">{{ $pendingCorrespondence }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-purple-50 rounded-bl-3xl -mr-4 -mt-4"></div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pre-Autorizaciones</p>
                    <p class="mt-1 text-2xl font-bold text-purple-600">{{ $pendingPreAuthorizations }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @can('access.register.entry')
                <a href="{{ route('access.logs.entry') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-emerald-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-500/0 group-hover:from-emerald-500/5 group-hover:to-emerald-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-emerald-700">Ingreso Peatonal</p>
                            <p class="text-xs text-gray-500 mt-0.5">Registrar visitante persona</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.manage.vehicle_access')
                <a href="{{ route('access.vehicle_access.entry') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-cyan-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-cyan-500/0 group-hover:from-cyan-500/5 group-hover:to-cyan-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-cyan-50 rounded-xl flex items-center justify-center group-hover:bg-cyan-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0V21h14v-5m0 0h3l2-4-2-4h-3l-2 4h-1"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-cyan-700">Ingreso Vehicular</p>
                            <p class="text-xs text-gray-500 mt-0.5">Registrar vehículo de residente</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.register.exit')
                <a href="{{ route('access.logs.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-red-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/0 to-red-500/0 group-hover:from-red-500/5 group-hover:to-red-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-red-700">Registrar Salida</p>
                            <p class="text-xs text-gray-500 mt-0.5">Marcar salida de persona/vehículo</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.manage.pre_authorizations')
                <a href="{{ route('access.pre_authorizations.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-purple-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-purple-500/0 group-hover:from-purple-500/5 group-hover:to-purple-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center group-hover:bg-purple-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-purple-700">Pre-Autorizaciones</p>
                            <p class="text-xs text-gray-500 mt-0.5">Ver y gestionar autorizaciones</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.manage.correspondence')
                <a href="{{ route('access.correspondence.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-pink-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-500/0 to-pink-500/0 group-hover:from-pink-500/5 group-hover:to-pink-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center group-hover:bg-pink-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-pink-700">Correspondencia</p>
                            <p class="text-xs text-gray-500 mt-0.5">Paquetes y encomiendas</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.manage.guard_logs')
                <a href="{{ route('access.guard_logs.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-amber-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-amber-500/0 group-hover:from-amber-500/5 group-hover:to-amber-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-amber-700">Minutas</p>
                            <p class="text-xs text-gray-500 mt-0.5">Novedades y reportes del guardia</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.register.entry')
                <a href="{{ route('access.logs.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-indigo-500/0 group-hover:from-indigo-500/5 group-hover:to-indigo-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center group-hover:bg-indigo-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700">Personas Dentro</p>
                            <p class="text-xs text-gray-500 mt-0.5">Ver quiénes están en el conjunto</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.view.reports')
                <a href="{{ route('access.reports.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-rose-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-rose-500/0 group-hover:from-rose-500/5 group-hover:to-rose-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center group-hover:bg-rose-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-rose-700">Reportes</p>
                            <p class="text-xs text-gray-500 mt-0.5">Estadísticas e informes</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('access.dashboard')
                <a href="{{ route('access.visitors.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:border-sky-300 hover:shadow-lg p-5 transition-all duration-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-sky-500/0 group-hover:from-sky-500/5 group-hover:to-sky-500/10 transition-all duration-200"></div>
                    <div class="relative flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center group-hover:bg-sky-100 group-hover:scale-110 transition-all duration-200">
                            <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-sky-700">Búsqueda Rápida</p>
                            <p class="text-xs text-gray-500 mt-0.5">Buscar visitantes, vehículos y más</p>
                        </div>
                    </div>
                </a>
                @endcan
            </div>

            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Personas Dentro del Conjunto</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Personas actualmente en las instalaciones</p>
                        </div>
                        @if($activeEntries > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                            {{ $activeEntries }} {{ $activeEntries === 1 ? 'persona' : 'personas' }}
                        </span>
                        @endif
                    </div>
                </div>

                @if($peopleInside->isEmpty())
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="mt-3 text-sm text-gray-500">No hay personas dentro del conjunto en este momento.</p>
                    @can('access.register.entry')
                    <a href="{{ route('access.logs.entry') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Registrar Ingreso
                    </a>
                    @endcan
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Persona</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Destino</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ingreso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tiempo</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($peopleInside as $log)
                            <tr class="{{ $log->alert_long_stay ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }} transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $log->alert_long_stay ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-indigo-500 to-indigo-700' }} flex items-center justify-center">
                                            <span class="text-xs font-bold text-white">{{ strtoupper(substr($log->person_name, 0, 2)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $log->person_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->person_doc }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {{ 
                                        str_contains($log->person_type, 'Vehicular') ? 'bg-cyan-50 text-cyan-700 ring-cyan-600/20' : 
                                        (str_contains($log->person_type, 'Residente') ? 'bg-teal-50 text-teal-700 ring-teal-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20') 
                                    }}">{{ $log->person_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->destination }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->location?->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->entry_time->format('H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm {{ $log->alert_long_stay ? 'text-red-700 font-semibold' : 'text-gray-600' }}">
                                            {{ $log->entry_time->diffForHumans(now(), true) }}
                                        </span>
                                        @if($log->alert_long_stay)
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <form action="{{ route('access.logs.exit', $log) }}" method="POST" onsubmit="return confirm('¿Registrar salida de {{ $log->person_name }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors shadow-sm">
                                            Salida
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            @php
                $longStayCount = $peopleInside->where('alert_long_stay', true)->count();
            @endphp
            @if($longStayCount > 0)
            <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="text-sm text-red-700 font-medium">
                            <strong>{{ $longStayCount }}</strong> {{ $longStayCount === 1 ? 'persona lleva' : 'personas llevan' }} más de 12 horas dentro del conjunto. Considere verificar.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>