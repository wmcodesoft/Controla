<x-access-layout>
    <div class="rounded-xl bg-gradient-to-r from-slate-800 to-indigo-900 p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-300">Reservas</p>
            <h2 class="text-xl font-bold text-white">Editar Zona</h2>
        </div>
        <a href="{{ route('access.zones.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
            <form method="POST" action="{{ route('access.zones.update', $zone) }}" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                @if($errors->any())
                    <div class="rounded-lg bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $zone->name) }}" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="ej. Salón social principal">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Tipo</label>
                        <select name="type" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['salon' => 'Salón social', 'piscina' => 'Piscina', 'gimnasio' => 'Gimnasio', 'parque' => 'Zona verde', 'cancha' => 'Cancha deportiva', 'biblioteca' => 'Biblioteca', 'otro' => 'Otra'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('type', $zone->type) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Capacidad (personas)</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $zone->capacity) }}" min="1" max="500" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Horario desde</label>
                        <input type="time" name="open_time" value="{{ old('open_time', $zone->open_time?->format('H:i')) }}" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Horario hasta</label>
                        <input type="time" name="close_time" value="{{ old('close_time', $zone->close_time?->format('H:i')) }}" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Vigencia desde</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at', $zone->starts_at?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Vigencia hasta</label>
                        <input type="date" name="ends_at" value="{{ old('ends_at', $zone->ends_at?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Descripción</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Reglas de uso, descripción...">{{ old('description', $zone->description) }}</textarea>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 text-sm text-slate-300">
                        <input type="checkbox" name="requires_approval" value="1" class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500" @checked(old('requires_approval', $zone->requires_approval))>
                        Requiere aprobación
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-300">
                        <input type="checkbox" name="is_active" value="1" class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active', $zone->is_active))>
                        Activa
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('access.zones.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg font-semibold text-xs text-slate-300 hover:bg-slate-700 transition-colors">Cancelar</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition-colors shadow-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-access-layout>
