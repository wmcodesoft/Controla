<x-resident-layout title="Nueva Pre-Autorización">
    <div class="max-w-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">Autorizar visita</h2>
        <form action="{{ route('resident.pre-authorizations.store') }}" method="POST" class="space-y-4 rounded-xl border border-slate-800 bg-slate-900 p-6">
            @csrf
            <div>
                <label class="block text-xs text-slate-400 mb-1">Nombre del visitante</label>
                <input type="text" name="visitor_name" value="{{ old('visitor_name') }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white" placeholder="Nombre completo">
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Documento del visitante</label>
                <input type="text" name="visitor_document" value="{{ old('visitor_document') }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white" placeholder="CC o NIT">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Fecha</label>
                    <input type="date" name="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Hora (opcional)</label>
                    <input type="time" name="scheduled_time" value="{{ old('scheduled_time') }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Ubicación / Portería</label>
                <select name="location_id" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                    @foreach ($locations as $loc)
                        <option value="{{ $loc->id }}" @selected(old('location_id') == $loc->id)>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Notas (opcional)</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">Autorizar visita</button>
        </form>
    </div>
</x-resident-layout>
