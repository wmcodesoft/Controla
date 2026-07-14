<x-client-layout title="Mascotas">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Mascotas</h2>
                <p class="text-sm text-slate-400 mt-1">Registro de mascotas por unidad.</p>
            </div>
            <a href="{{ route('client.pets.create') }}" class="inline-flex rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">
                Nueva mascota
            </a>
        </div>

        <form method="GET" class="flex flex-wrap gap-3">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar nombre o raza"
                   class="rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-white">
            <select name="structure_id" class="rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-white">
                <option value="">Todas las unidades</option>
                @foreach ($structures as $structure)
                    <option value="{{ $structure->id }}" @selected(request('structure_id') == $structure->id)>{{ $structure->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Filtrar</button>
        </form>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Unidad</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Especie</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Raza</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-slate-500">Peligroso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($pets as $pet)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-3">
                                <a href="{{ route('client.pets.show', $pet) }}" class="font-medium text-white hover:text-teal-300">{{ $pet->name }}</a>
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $pet->structure?->name }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $pet->species->label() }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $pet->breed ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($pet->is_potentially_dangerous)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-900/50 text-red-300">Sí</span>
                                @else
                                    <span class="text-slate-600">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No hay mascotas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $pets->links() }}
    </div>
</x-client-layout>
