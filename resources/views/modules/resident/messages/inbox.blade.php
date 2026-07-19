<x-resident-layout title="Mensajes">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Mensajería Interna</h2>
                <p class="text-sm text-slate-400 mt-1">Bandeja de entrada</p>
            </div>
            <a href="{{ route('resident.messages.create') }}" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">
                <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Redactar
            </a>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('resident.messages.inbox') }}" class="rounded-lg bg-teal-600 px-3 py-1.5 text-xs font-semibold text-white">Recibidos @if($unreadCount > 0)<span class="ml-1.5 bg-white text-teal-700 rounded-full px-1.5 py-0.5 text-[10px]">{{ $unreadCount }}</span>@endif</a>
            <a href="{{ route('resident.messages.sent') }}" class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-700 transition-colors">Enviados</a>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">De</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Asunto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($messages as $message)
                        <tr class="hover:bg-slate-800/40 {{ is_null($message->read_at) ? 'bg-slate-800/20' : '' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ strtoupper(substr($message->sender->name, 0, 2)) }}
                                    </div>
                                    <span class="text-white">{{ $message->sender->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('resident.messages.show', $message) }}" class="text-white hover:text-teal-300 font-medium">
                                    @if(is_null($message->read_at))<span class="w-2 h-2 bg-teal-400 rounded-full inline-block mr-1.5"></span>@endif
                                    {{ $message->subject }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-slate-400">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                @if(is_null($message->read_at))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-900/50 text-teal-300">No leído</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">Leído</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">Bandeja vacía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    </div>
</x-resident-layout>