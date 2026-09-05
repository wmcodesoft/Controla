<x-access-layout title="Centro de Operaciones">
    <div class="-mt-6 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pt-6 pb-8 bg-gradient-to-r from-slate-800 to-indigo-900 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-300">Módulo de Acceso</p>
                <h2 class="text-xl font-bold text-white">Centro de Operaciones</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-400/20 text-emerald-300 ring-1 ring-emerald-400/30">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5"></span>
                    Sistema Activo
                </span>
                <span class="hidden sm:inline-flex items-center text-sm text-indigo-200">
                    {{ now()->format('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="relative bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group hover:border-slate-700 transition-all duration-200">
            <div class="absolute inset-y-0 left-0 w-1.5 bg-emerald-500"></div>
            <div class="p-4 pl-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dentro</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $activeEntries }}</p>
            </div>
        </div>
        <div class="relative bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group hover:border-slate-700 transition-all duration-200">
            <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-500"></div>
            <div class="p-4 pl-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hoy</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $todayEntries }}</p>
            </div>
        </div>
        <div class="relative bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group hover:border-slate-700 transition-all duration-200">
            <div class="absolute inset-y-0 left-0 w-1.5 bg-indigo-500"></div>
            <div class="p-4 pl-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Visitantes</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $totalVisitors }}</p>
            </div>
        </div>
        <div class="relative bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group hover:border-slate-700 transition-all duration-200">
            <div class="absolute inset-y-0 left-0 w-1.5 bg-teal-500"></div>
            <div class="p-4 pl-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Personas</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $totalResidents }}</p>
            </div>
        </div>
        <div class="relative bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group hover:border-slate-700 transition-all duration-200">
            <div class="absolute inset-y-0 left-0 w-1.5 bg-amber-500"></div>
            <div class="p-4 pl-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Correspondencia</p>
                <p class="mt-1 text-2xl font-bold text-amber-400">{{ $pendingCorrespondence }}</p>
            </div>
        </div>
        <div class="relative bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group hover:border-slate-700 transition-all duration-200">
            <div class="absolute inset-y-0 left-0 w-1.5 bg-purple-500"></div>
            <div class="p-4 pl-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pre-Autoriz.</p>
                <p class="mt-1 text-2xl font-bold text-purple-400">{{ $pendingPreAuthorizations }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-slate-900 rounded-xl border border-slate-800 p-5">
            <h3 class="text-sm font-semibold text-white mb-1">Accesos diarios (últimos 7 días)</h3>
            <p class="text-xs text-slate-500 mb-4">Movimientos de entrada registrados por día</p>
            <div class="relative h-64 overflow-y-auto">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
        <div class="bg-slate-900 rounded-xl border border-slate-800 p-5">
            <h3 class="text-sm font-semibold text-white mb-1">Distribución por tipo</h3>
                <p class="text-xs text-slate-500 mb-4">Visitantes peatonales vs vehiculares vs personas</p>
            <div class="relative h-64 overflow-y-auto">
                <canvas id="typeChart"></canvas>
            </div>
        </div>
        <div class="bg-slate-900 rounded-xl border border-slate-800 p-5 lg:col-span-2">
            <h3 class="text-sm font-semibold text-white mb-1">Actividad hoy por hora</h3>
            <p class="text-xs text-slate-500 mb-4">Distribución horaria de ingresos del día</p>
            <div class="relative h-56 overflow-y-auto">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Personas Dentro del Conjunto</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Personas actualmente en las instalaciones</p>
                </div>
                @if($activeEntries > 0)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-900/30 text-emerald-300 ring-1 ring-emerald-700">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                    {{ $activeEntries }} {{ $activeEntries === 1 ? 'persona' : 'personas' }}
                </span>
                @endif
            </div>
        </div>

        @if($peopleInside->isEmpty())
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-16 w-16 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="mt-3 text-sm text-slate-500">No hay personas dentro del conjunto en este momento.</p>
            @can('access.register.entry')
            <a href="{{ route('access.logs.entry') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Registrar Ingreso
            </a>
            @endcan
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Persona</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Documento</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Destino</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ingreso</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiempo</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @foreach($peopleInside as $log)
                    <tr class="{{ $log->alert_long_stay ? 'bg-red-900/20 hover:bg-red-900/30' : 'hover:bg-slate-800/40' }} transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $log->alert_long_stay ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-indigo-500 to-indigo-700' }} flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($log->person_name, 0, 2)) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-white">{{ $log->person_name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->person_doc }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {{ 
                                str_contains($log->person_type, 'Vehicular') ? 'bg-cyan-900/30 text-cyan-300 ring-cyan-700' : 
                                (str_contains($log->person_type, 'Persona') ? 'bg-teal-900/30 text-teal-300 ring-teal-700' : 'bg-blue-900/30 text-blue-300 ring-blue-700') 
                            }}">{{ $log->person_type }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->destination }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->location?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->entry_time->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm {{ $log->alert_long_stay ? 'text-red-700 font-semibold' : 'text-slate-400' }}">
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
    <div class="mt-4 bg-red-900/20 border border-red-800 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm text-red-300 font-medium">
                    <strong>{{ $longStayCount }}</strong> {{ $longStayCount === 1 ? 'persona lleva' : 'personas llevan' }} más de {{ config('access.alerts.long_stay_hours') }} horas dentro del conjunto. Considere verificar.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="mt-8 bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-white">Accesos Recientes</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Últimos movimientos registrados en el sistema</p>
                </div>
                <a href="{{ route('access.logs.index') }}" class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors">Ver todos →</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead>
                    <tr class="bg-slate-950/60">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Persona</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ingreso</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($recentLogs as $log)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($log->visitor?->full_name ?? $log->resident?->full_name ?? '?', 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-white">{{ $log->visitor?->full_name ?? $log->resident?->full_name ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->access_type == 'visitor_vehicle' ? 'bg-cyan-900/30 text-cyan-300 ring-1 ring-cyan-700' : 'bg-blue-900/30 text-blue-300 ring-1 ring-blue-700' }}">
                                {{ $log->access_type == 'visitor_vehicle' ? 'Visit. Vehicular' : 'Visitante' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->location?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $log->entry_time->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->status === 'active')
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
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="mt-2 text-sm text-slate-500">Sin registros recientes</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;

    const dailyCtx = document.getElementById('dailyChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: @json($dailyLabels),
                datasets: [{
                    label: 'Ingresos',
                    data: @json($dailyData),
                    backgroundColor: 'rgba(99, 102, 241, 0.3)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } }, x: { ticks: { color: '#94a3b8' }, grid: { display: false } } }
            }
        });
    }

    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: @json($typeLabels),
                datasets: [{
                    data: @json($typeData),
                    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(6, 182, 212, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, color: '#94a3b8' } } }
            }
        });
    }

    const hourlyCtx = document.getElementById('hourlyChart');
    if (hourlyCtx) {
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: @json($hourlyLabels),
                datasets: [{
                    label: 'Ingresos',
                    data: @json($hourlyData),
                    borderColor: 'rgba(139, 92, 246, 1)',
                    backgroundColor: 'rgba(139, 92, 246, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                    x: { ticks: { maxTicksLimit: 12, color: '#94a3b8' }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush

</x-access-layout>
