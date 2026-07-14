<x-resident-layout title="Pre-Autorizaciones">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Mis Pre-Autorizaciones</h2>
            <a href="{{ route('resident.pre-authorizations.create') }}" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">Nueva</a>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Visitante</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Ubicación</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($preAuthorizations as $pa)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-3">
                                <p class="text-white">{{ $pa->visitor?->full_name ?? '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $pa->notes ? Str::limit($pa->notes, 40) : '' }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $pa->scheduled_date->format('d/m/Y') }} {{ $pa->scheduled_time ? $pa->scheduled_time->format('H:i') : '' }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $pa->location?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{
                                    $pa->status === 'pending' ? 'bg-yellow-900/50 text-yellow-300' :
                                    ($pa->status === 'used' ? 'bg-green-900/50 text-green-300' :
                                    ($pa->status === 'expired' ? 'bg-slate-800 text-slate-400' : 'bg-red-900/50 text-red-300'))
                                }}">{{ $pa->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($pa->status === 'pending')
                                    <form action="{{ route('resident.pre-authorizations.destroy', $pa) }}" method="POST" onsubmit="return confirm('¿Cancelar esta pre-autorización?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-400 hover:text-red-300">Cancelar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No tienes pre-autorizaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $preAuthorizations->links() }}
    </div>
</x-resident-layout>
