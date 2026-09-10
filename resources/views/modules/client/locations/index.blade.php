<x-client-layout title="Puntos de acceso">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Puntos de acceso</h2>
                <p class="text-sm text-slate-400 mt-1">Puertas, porterías y puntos de control.</p>
            </div>
            <a href="{{ route('client.locations.create') }}" class="inline-flex rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">
                Nuevo punto
            </a>
        </div>

        <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Dirección</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-slate-500">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($locations as $location)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-3 font-mono text-xs text-slate-300">{{ $location->code }}</td>
                            <td class="px-4 py-3 font-medium text-white">{{ $location->name }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $location->address ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($location->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-900/50 text-emerald-300">Activo</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-900/50 text-red-300">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('client.locations.edit', $location) }}" class="text-teal-400 hover:text-teal-300">Editar</a>
                                <form action="{{ route('client.locations.destroy', $location) }}" method="POST" class="inline ml-2" onsubmit="return confirm('¿Eliminar este punto?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No hay puntos de acceso registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $locations->links() }}
    </div>
</x-client-layout>
