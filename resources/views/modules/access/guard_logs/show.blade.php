<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minuta de Vigilancia</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">Guardia</dt><dd class="mt-1 text-sm text-gray-900">{{ $guardLog->user->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Ubicación</dt><dd class="mt-1 text-sm text-gray-900">{{ $guardLog->location->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Fecha/Hora</dt><dd class="mt-1 text-sm text-gray-900">{{ $guardLog->log_time->format('d/m/Y H:i') }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Tipo</dt><dd class="mt-1 text-sm text-gray-900">{{ ucfirst($guardLog->type) }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Turno</dt><dd class="mt-1 text-sm text-gray-900">{{ ucfirst($guardLog->shift_type) }}</dd></div>
                </dl>
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $guardLog->description }}</dd>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
