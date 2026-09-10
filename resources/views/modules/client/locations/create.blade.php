<x-client-layout title="Nuevo punto de acceso">
    <div class="max-w-2xl">
        <a href="{{ route('client.locations.index') }}" class="text-sm text-teal-400 hover:text-teal-300">← Puntos de acceso</a>
        <h2 class="text-2xl font-bold text-white mt-2 mb-6">Registrar punto de acceso</h2>
        <form action="{{ route('client.locations.store') }}" method="POST" class="space-y-4 rounded-xl border border-slate-800 bg-slate-900 p-6">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Código</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white" placeholder="PT-01">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white" placeholder="Portería principal">
                </div>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Radio geo (m)</label>
                    <input type="number" name="geo_radius_m" value="{{ old('geo_radius_m', 250) }}" min="10" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Latitud</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Longitud</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm text-white">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-600 bg-slate-950 text-teal-600">
                Activo
            </label>
            <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">Crear punto</button>
        </form>
    </div>
</x-client-layout>
