<x-client-layout title="Lista de Bloqueo">
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Lista de Bloqueo</h2>
            <p class="text-sm text-slate-400 mt-1">Personas y vehículos bloqueados del conjunto.</p>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Motivo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Bloqueado por</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fecha</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-slate-500">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($entries as $entry)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $entry->isVehicle() ? 'bg-cyan-900/50 text-cyan-300' : 'bg-amber-900/50 text-amber-300' }}">
                                    {{ $entry->typeLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $entry->reason }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $entry->blocker?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $entry->blocked_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($entry->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-900/50 text-red-300">Activo</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">Removido</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                @if($entry->is_active)
                                    <form action="{{ route('client.blocklist.destroy', $entry) }}" method="POST" class="inline" onsubmit="return confirm('¿Remover este bloqueo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-teal-400 hover:text-teal-300">Remover</button>
                                    </form>
                                @else
                                    <span class="text-slate-600">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">No hay entradas en la lista de bloqueo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $entries->links() }}
    </div>
</x-client-layout>
