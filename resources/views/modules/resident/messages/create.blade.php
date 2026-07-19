<x-resident-layout title="Redactar mensaje">
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('resident.messages.inbox') }}" class="text-sm text-teal-400 hover:text-teal-300">← Bandeja</a>
            <h2 class="text-2xl font-bold text-white">Redactar mensaje</h2>
        </div>
        <form action="{{ route('resident.messages.store') }}" method="POST" class="space-y-4 rounded-xl border border-slate-800 bg-slate-900 p-6">
            @csrf
            <div>
                <label class="block text-xs text-slate-400 mb-1">Destinatario</label>
                <select name="recipient_id" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                    <option value="">Todos los residentes (comunicado general)</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('recipient_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Asunto</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="200" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white" placeholder="Asunto del mensaje">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Mensaje</label>
                <textarea name="body" rows="6" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white" placeholder="Escriba su mensaje...">{{ old('body') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">
                    <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Enviar
                </button>
                <a href="{{ route('resident.messages.inbox') }}" class="rounded-lg bg-slate-700 px-4 py-2 text-sm text-white hover:bg-slate-600">Cancelar</a>
            </div>
        </form>
    </div>
</x-resident-layout>