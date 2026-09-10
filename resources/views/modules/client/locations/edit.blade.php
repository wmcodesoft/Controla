<x-client-layout title="Editar punto de acceso">
    <div class="max-w-2xl">
        <a href="{{ route('client.locations.index') }}" class="text-sm text-teal-400 hover:text-teal-300">← Puntos de acceso</a>
        <h2 class="text-2xl font-bold text-white mt-2 mb-6">Editar punto de acceso</h2>
        <form action="{{ route('client.locations.update', $location) }}" method="POST" class="space-y-4 rounded-xl border border-slate-800 bg-slate-900 p-6">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Código</label>
                    <input type="text" name="code" value="{{ old('code', $location->code) }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $location->name) }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address', $location->address) }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $location->phone) }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Radio geo (m)</label>
                    <input type="number" name="geo_radius_m" value="{{ old('geo_radius_m', $location->geo_radius_m) }}" min="10" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Latitud</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $location->latitude) }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Longitud</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $location->longitude) }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-600 bg-slate-950 text-teal-600" @checked(old('is_active', $location->is_active))>
                Activo
            </label>
            <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">Guardar cambios</button>
        </form>
    </div>
</x-client-layout>
