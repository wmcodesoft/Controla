<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Correspondencia</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('modules.access.partials.subnav')
            <div class="mt-6 flex justify-end">
                <a href="{{ route('access.correspondence.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Registrar Correspondencia</a>
            </div>
            <div class="mt-4 bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destinatario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transportadora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recibido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($correspondence as $c)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($c->resident)
                                    {{ $c->resident->full_name }}
                                @elseif($c->housingUnit)
                                    {{ $c->housingUnit->full_label }}
                                @elseif($c->host)
                                    {{ $c->host->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($c->package_type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $c->carrier ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $c->received_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $c->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $c->status == 'pending' ? 'Pendiente' : 'Entregado' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('access.correspondence.show', $c) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                @if($c->status == 'pending')
                                <form action="{{ route('access.correspondence.deliver', $c) }}" method="POST" class="inline" onsubmit="return confirm('¿Marcar como entregado?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">Entregar</button>
                                </form>
                                @endif
                                <form action="{{ route('access.correspondence.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $correspondence->links() }}</div>
        </div>
    </div>
</x-app-layout>
