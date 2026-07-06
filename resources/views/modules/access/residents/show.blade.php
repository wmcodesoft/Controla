<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $resident->full_name }}</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('modules.access.partials.subnav')

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs text-gray-500 uppercase">Documento</dt>
                                <dd class="text-sm font-medium">{{ $resident->document_type }} {{ $resident->document_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase">Nombre</dt>
                                <dd class="text-sm font-medium">{{ $resident->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase">Teléfono</dt>
                                <dd class="text-sm">{{ $resident->phone ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase">Email</dt>
                                <dd class="text-sm">{{ $resident->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase">Tipo</dt>
                                <dd class="text-sm">{{ ucfirst($resident->resident_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase">Estado</dt>
                                <dd><span class="px-2 py-1 text-xs rounded-full {{ $resident->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $resident->is_active ? 'Activo' : 'Inactivo' }}</span></dd>
                            </div>
                        </dl>
                        <div class="mt-4">
                            <a href="{{ route('access.residents.edit', $resident) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Editar</a>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Viviendas</h3>
                        @forelse($resident->housingUnits as $hu)
                        <div class="mb-2 p-2 bg-gray-50 rounded">
                            <p class="text-sm font-medium">{{ $hu->building->name ?? '' }} - {{ $hu->unit_number }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($hu->pivot->relationship_type) }} @if($hu->pivot->is_primary) <span class="text-indigo-600">(Principal)</span> @endif</p>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Sin viviendas asignadas</p>
                        @endforelse
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Vehículos</h3>
                        @forelse($resident->vehicles as $v)
                        <div class="mb-2 p-2 bg-gray-50 rounded flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium">{{ $v->plate }}</p>
                                <p class="text-xs text-gray-500">{{ $v->brand }} {{ $v->model }} ({{ $v->color }})</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ ucfirst($v->type) }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Sin vehículos registrados</p>
                        @endforelse
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Últimos Accesos</h3>
                        @if($resident->accessLogs->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($resident->accessLogs as $log)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $log->entry_time->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $log->access_type }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $log->location->name ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $log->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $log->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-sm text-gray-500">Sin registros de acceso</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
