<x-access-layout title="Vehículos">
    <div class=" bg-gradient-to-r from-slate-800 to-indigo-900 p-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-300">Vehículos</p>
            <h2 class="text-xl font-bold text-white">Gestión Vehicular</h2>
        </div>
        <a href="{{ route('access.vehicles.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nuevo Vehículo
        </a>
    </div>

    @if(session('success'))
    <div class="rounded-lg bg-emerald-900/40 border border-emerald-700 text-emerald-200 px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div x-data="{ tab: 'control' }" >
        <div class="flex gap-1.5 -mb-px">
            <button @click="tab = 'control'" class="admin-header-tab" :class="tab === 'control' && 'is-active'">Control</button>
            <button @click="tab = 'registro'" class="admin-header-tab" :class="tab === 'registro' && 'is-active'">Registro</button>
        </div>

        {{-- TAB: CONTROL --}}
        <div x-show="tab === 'control'" class="mt-6 space-y-6">
            @if($activeLogs->count())
            <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-white">Vehículos Dentro</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Vehículos actualmente en el conjunto</p>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-cyan-900/30 text-cyan-300 ring-1 ring-cyan-700 uppercase">{{ $log->vehicle->plate }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase {{ $isResident ? 'bg-teal-900/30 text-teal-300' : 'bg-emerald-900/30 text-emerald-300' }}">
                                        {{ $isResident ? 'Persona' : 'Visitante' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $personName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->vehicle->brand }} {{ $log->vehicle->model }} <span class="text-slate-500">({{ $log->vehicle->color }})</span></td>
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
            @endif

            <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800">
                    <h3 class="text-base font-semibold text-white">Registros del Día</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Todos los movimientos vehiculares de hoy</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-800">
                        <thead class="bg-slate-950/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Placa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Persona</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ingreso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Salida</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse($todayLogs as $log)
                            @php
                                $personName = $log->resident?->full_name ?? $log->visitor?->full_name ?? '-';
                                $isResident = $log->resident_id !== null;
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-cyan-900/30 text-cyan-300 ring-1 ring-cyan-700 uppercase">{{ $log->vehicle->plate }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase {{ $isResident ? 'bg-teal-900/30 text-teal-300' : 'bg-emerald-900/30 text-emerald-300' }}">
                                        {{ $isResident ? 'Persona' : 'Visitante' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $personName }}</td>
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0V21h14v-5m0 0h3l2-4-2-4h-3l-2 4h-1"/></svg>
                                    <p class="mt-2 text-sm text-slate-500">Sin registros hoy</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $todayLogs->links() }}</div>
        </div>

        {{-- TAB: REGISTRO --}}
        <div x-show="tab === 'registro'" class="mt-6">
            <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-800">
                    <thead class="bg-slate-950/60">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Placa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Marca</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Modelo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Color</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Propietario</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white uppercase">{{ $vehicle->plate }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->brand ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->model ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->color ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ ucfirst($vehicle->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->resident?->full_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('access.vehicles.edit', $vehicle) }}" class="text-indigo-400 hover:text-indigo-300">Editar</a>
                                <form action="{{ route('access.vehicles.destroy', $vehicle) }}" method="POST" class="inline ml-2" onsubmit="return confirm('¿Eliminar vehículo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">Sin vehículos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $vehicles->links() }}</div>
        </div>
    </div>
</x-access-layout>
