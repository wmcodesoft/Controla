<x-access-layout title="Vehículos Registrados">
    <div class=" bg-gradient-to-r from-slate-800 to-indigo-900 p-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-300">Vehículos</p>
            <h2 class="text-xl font-bold text-white">Vehículos Registrados</h2>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mt-6 mb-6 bg-slate-900 rounded-xl border border-slate-800 p-1 w-fit">
        <a href="{{ route('access.vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-400 hover:text-white hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Control
        </a>
        <a href="{{ route('access.vehicles.active') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors text-slate-400 hover:text-white hover:bg-slate-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Vehículos Dentro
        </a>
        <a href="{{ route('access.vehicles.list') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-indigo-600 text-white">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Vehículos Registrados
        </a>
    </div>

    <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-950/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Placa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Marca</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Modelo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Propietario</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white uppercase">{{ $vehicle->plate }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->brand ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->model ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->color ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ ucfirst($vehicle->type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $vehicle->resident?->full_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('access.vehicles.edit', $vehicle) }}" class="text-indigo-400 hover:text-indigo-300">Editar</a>
                            <form action="{{ route('access.vehicles.destroy', $vehicle) }}" method="POST" class="inline ml-2" onsubmit="return confirm('¿Eliminar vehículo?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">Sin vehículos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $vehicles->links() }}</div>
</x-access-layout>
