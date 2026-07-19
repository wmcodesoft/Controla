<x-access-layout>
    <div class="-mt-6 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pt-6 pb-8 bg-gradient-to-r from-slate-800 to-indigo-900 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-300">Control de Acceso</p>
                <h2 class="text-xl font-bold text-white">Visitantes</h2>
            </div>
        </div>
    </div>

    @include('modules.access.partials.subnav')

    <div class="mt-6 flex justify-end">
        <a href="{{ route('access.visitors.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Nuevo Visitante</a>
    </div>

    <div class="mt-4 bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-800">
            <thead class="bg-slate-950/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Documento</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Teléfono</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($visitors as $visitor)
                <tr class="hover:bg-slate-800/40">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $visitor->document_type }} {{ $visitor->document_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $visitor->full_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $visitor->company ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ ucfirst($visitor->visitor_type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $visitor->phone ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('access.visitors.show', $visitor) }}" class="text-indigo-400 hover:text-indigo-300">Ver</a>
                        <a href="{{ route('access.visitors.edit', $visitor) }}" class="text-yellow-500 hover:text-yellow-400">Editar</a>
                        <form action="{{ route('access.visitors.destroy', $visitor) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este visitante?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $visitors->links() }}</div>
</x-access-layout>
