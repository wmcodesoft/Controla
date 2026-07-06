<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $visitor->full_name }}</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">Documento</dt><dd class="mt-1 text-sm text-gray-900">{{ $visitor->document_type }} {{ $visitor->document_number }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Teléfono</dt><dd class="mt-1 text-sm text-gray-900">{{ $visitor->phone ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $visitor->email ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Empresa</dt><dd class="mt-1 text-sm text-gray-900">{{ $visitor->company ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Tipo</dt><dd class="mt-1 text-sm text-gray-900">{{ ucfirst($visitor->visitor_type) }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Nacionalidad</dt><dd class="mt-1 text-sm text-gray-900">{{ $visitor->nationality ?? '-' }}</dd></div>
                </dl>
            </div>

            @if($visitor->vehicles->count())
            <div class="mt-6 bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Vehículos</h3>
                <ul class="list-disc list-inside text-sm text-gray-600">
                    @foreach($visitor->vehicles as $v)
                    <li>{{ $v->plate }} - {{ $v->brand }} {{ $v->model }} ({{ $v->color }})</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mt-6 bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historial de Accesos</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ingreso</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Salida</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($visitor->accessLogs as $log)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $log->entry_time->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $log->location->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $log->entry_time->format('H:i') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $log->exit_time?->format('H:i') ?? '-' }}</td>
                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded-full {{ $log->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $log->status == 'active' ? 'Dentro' : 'Salió' }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-2 text-sm text-gray-500 text-center">Sin accesos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>