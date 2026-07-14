<x-resident-layout title="Correspondencia">
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-white">Mi Correspondencia</h2>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Recibido</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Mensajero</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($correspondence as $c)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-3">
                                <a href="{{ route('resident.correspondence.show', $c) }}" class="text-white hover:text-teal-300">{{ $c->received_at->format('d/m/Y H:i') }}</a>
                            </td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-xs bg-slate-800 text-slate-300">{{ $c->package_type }}</span></td>
                            <td class="px-4 py-3 text-slate-400">{{ $c->carrier ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{
                                    $c->status === 'pending' ? 'bg-yellow-900/50 text-yellow-300' : 'bg-green-900/50 text-green-300'
                                }}">{{ $c->status === 'pending' ? 'Pendiente' : 'Entregado' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">Sin correspondencia registrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $correspondence->links() }}
    </div>
</x-resident-layout>
