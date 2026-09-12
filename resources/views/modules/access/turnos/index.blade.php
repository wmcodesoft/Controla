<x-access-layout>
    <div class="-mt-6 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pt-6 pb-8 bg-gradient-to-r from-slate-800 to-indigo-900 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-300">Control de Acceso</p>
                <h2 class="text-xl font-bold text-white">Mis Turnos</h2>
            </div>
            @if($currentShift)
                <button type="button" onclick="document.getElementById('closeModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-500 transition-colors shadow-sm">Cerrar Turno</button>
            @else
                <a href="{{ route('access.turnos.open') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-500 transition-colors shadow-sm">Abrir Turno</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @if($currentShift)
            <div class="bg-emerald-900/40 border border-emerald-700 rounded-xl p-5 md:col-span-3">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-emerald-400 font-semibold">Turno en curso</p>
                        <p class="mt-1 text-2xl font-bold text-white">Desde {{ $currentShift->started_at->format('H:i') }} h</p>
                        <div class="mt-2 flex flex-wrap gap-3 text-xs text-slate-300">
                            <span class="inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Activo</span>
                            @if($currentShift->location)
                                <span class="inline-flex items-center gap-1">📍 {{ $currentShift->location->name }}</span>
                            @endif
                            <span>🗓 {{ $currentShift->started_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="text-right text-xs text-slate-400">
                        <p>Duración actual</p>
                        <p class="text-lg font-bold text-emerald-300">{{ $currentShift->started_at->diffForHumans(now(), ['parts' => 2, 'short' => true]) }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-amber-900/30 border border-amber-700/70 rounded-xl p-5 md:col-span-3">
                <p class="text-sm text-amber-200">No tienes un turno abierto. <a href="{{ route('access.turnos.open') }}" class="font-semibold underline">Abrir turno</a> para habilitar las operaciones de portería.</p>
            </div>
        @endif
    </div>

    <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-200">Historial de turnos</h3>
            <span class="text-xs text-slate-500">{{ $history->total() }} registros</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-slate-500 border-b border-slate-800">
                            <th class="px-6 py-3">Inicio</th>
                            <th class="px-6 py-3">Fin</th>
                            <th class="px-6 py-3">Duración</th>
                            <th class="px-6 py-3">Ubicación</th>
                            <th class="px-6 py-3">Nota Apertura</th>
                            <th class="px-6 py-3">Nota Cierre</th>
                        </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse($history as $shift)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-3 text-white">{{ $shift->started_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3 text-slate-300">{{ $shift->ended_at ? $shift->ended_at->format('d/m/Y H:i') : '—' }}</td>
                            <td class="px-6 py-3 text-slate-400">
                                @if($shift->ended_at)
                                    {{ (int)$shift->started_at->diffInMinutes($shift->ended_at) / 60 >= 1 ? number_format($shift->started_at->diffInHours($shift->ended_at), 1) . ' h' : $shift->started_at->diffInMinutes($shift->ended_at) . ' min' }}
                                @else
                                    <span class="text-emerald-400 font-medium">En curso</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-slate-400">{{ $shift->location?->name ?? 'No especificada' }}</td>
                            <td class="px-6 py-3 text-slate-400 max-w-xs truncate">{{ $shift->start_notes ?? '—' }}</td>
                            <td class="px-6 py-3 text-slate-400 max-w-xs truncate">{{ $shift->end_notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">Sin turnos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-800">
            {{ $history->links() }}
        </div>
    </div>

    @if($currentShift)
    <div id="closeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 rounded-xl border border-slate-700 shadow-2xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-900/60 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Cerrar Turno</h3>
                    <p class="text-xs text-slate-400">Turno activo desde {{ $currentShift->started_at->format('H:i') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('access.turnos.close') }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-300">Nota de cierre</label>
                    <textarea name="end_notes" rows="3" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500" placeholder="Observaciones al cerrar el turno..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('closeModal').classList.add('hidden')" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg font-semibold text-xs text-slate-300 hover:bg-slate-700 transition-colors">Cancelar</button>
                    <button type="submit" onclick="return confirm('¿Cerrar el turno actual?')" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-500 transition-colors shadow-sm">Cerrar Turno</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-access-layout>