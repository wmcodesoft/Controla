<x-resident-layout title="Mensajes enviados">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Mensajería Interna</h2>
                <p class="text-sm text-slate-400 mt-1">Mensajes enviados</p>
            </div>
            <a href="{{ route('resident.messages.create') }}" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">Redactar</a>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('resident.messages.inbox') }}" class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-700 transition-colors">Recibidos</a>
            <a href="{{ route('resident.messages.sent') }}" class="rounded-lg bg-teal-600 px-3 py-1.5 text-xs font-semibold text-white">Enviados</a>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Para</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Asunto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($messages as $message)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ strtoupper(substr($message->recipient?->name ?? '?', 0, 2)) }}
                                    </div>
                                    <span class="text-white">{{ $message->recipient?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('resident.messages.show', $message) }}" class="text-white hover:text-teal-300 font-medium">{{ $message->subject }}</a>
                            </td>
                            <td class="px-4 py-3 text-slate-400">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">Enviado</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">No has enviado mensajes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    </div>
</x-resident-layout>