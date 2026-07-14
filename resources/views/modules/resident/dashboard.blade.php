<x-resident-layout title="Resumen">
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Bienvenido, {{ auth()->user()?->name }}</h2>
            <p class="text-sm text-slate-400 mt-1">Panel de residente - gestión de visitas y correspondencia.</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase text-slate-500">Pre-Autorizaciones activas</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $preAuthorizations->whereIn('status', ['pending', 'used'])->count() }}</p>
            </div>
            <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase text-slate-500">Correspondencia pendiente</p>
                <p class="text-2xl font-bold text-amber-400 mt-1">{{ $pendingCorrespondence->count() }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden">
            <div class="px-4 py-3 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-white">Últimas Pre-Autorizaciones</h3>
                <a href="{{ route('resident.pre-authorizations.create') }}" class="text-xs text-teal-400 hover:text-teal-300">Nueva</a>
            </div>
            <table class="min-w-full text-sm">
                <tbody class="divide-y divide-slate-800">
                    @forelse ($preAuthorizations as $pa)
                        <tr>
                            <td class="px-4 py-3 text-white">{{ $pa->visitor?->full_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $pa->scheduled_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{
                                    $pa->status === 'pending' ? 'bg-yellow-900/50 text-yellow-300' :
                                    ($pa->status === 'used' ? 'bg-green-900/50 text-green-300' :
                                    ($pa->status === 'cancelled' ? 'bg-red-900/50 text-red-300' : 'bg-slate-800 text-slate-400'))
                                }}">{{ $pa->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-slate-500">Sin pre-autorizaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden">
            <div class="px-4 py-3 bg-slate-950/60 border-b border-slate-800">
                <h3 class="text-sm font-semibold text-white">Correspondencia Pendiente</h3>
            </div>
            <table class="min-w-full text-sm">
                <tbody class="divide-y divide-slate-800">
                    @forelse ($pendingCorrespondence as $c)
                        <tr>
                            <td class="px-4 py-3 text-white">{{ $c->carrier ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $c->package_type }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $c->received_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-slate-500">Sin correspondencia pendiente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-resident-layout>
