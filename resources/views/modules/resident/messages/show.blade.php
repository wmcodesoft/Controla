<x-resident-layout title="Mensaje">
    <div class="max-w-2xl">
        <a href="{{ $message->sender_id === auth()->id() ? route('resident.messages.sent') : route('resident.messages.inbox') }}" class="text-sm text-teal-400 hover:text-teal-300">← Volver</a>

        <div class="mt-4 rounded-xl border border-slate-800 bg-slate-900 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr($message->sender->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-white">{{ $message->sender->name }}</p>
                    <p class="text-xs text-slate-500">Para: {{ $message->recipient?->name ?? 'Todos los residentes' }}</p>
                </div>
                <span class="text-xs text-slate-500">{{ $message->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="px-6 py-4">
                <h3 class="text-base font-bold text-white mb-4">{{ $message->subject }}</h3>
                <div class="text-sm text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $message->body }}</div>
            </div>
            <div class="px-6 py-3 bg-slate-950/30 border-t border-slate-800 flex justify-between items-center">
                <span class="text-xs text-slate-600">
                    @if($message->read_at)Leído {{ $message->read_at->format('d/m/Y H:i') }}@else Enviado {{ $message->created_at->format('d/m/Y H:i') }}@endif
                </span>
                @if($message->recipient_id !== auth()->id())
                <a href="{{ route('resident.messages.create') }}?subject=Re: {{ $message->subject }}" class="text-xs text-teal-400 hover:text-teal-300">Responder</a>
                @endif
            </div>
        </div>
    </div>
</x-resident-layout>