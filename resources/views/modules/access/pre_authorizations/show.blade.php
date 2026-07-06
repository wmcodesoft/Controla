<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pre-Autorización</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">Visitante</dt><dd class="mt-1 text-sm text-gray-900">{{ $preAuthorization->visitor->full_name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Documento</dt><dd class="mt-1 text-sm text-gray-900">{{ $preAuthorization->visitor->document_type }} {{ $preAuthorization->visitor->document_number }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Anfitrión</dt><dd class="mt-1 text-sm text-gray-900">{{ $preAuthorization->host->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Ubicación</dt><dd class="mt-1 text-sm text-gray-900">{{ $preAuthorization->location->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Fecha</dt><dd class="mt-1 text-sm text-gray-900">{{ $preAuthorization->scheduled_date->format('d/m/Y') }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Estado</dt><dd class="mt-1 text-sm"><span class="px-2 py-1 text-xs rounded-full @if($preAuthorization->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($preAuthorization->status == 'used') bg-blue-100 text-blue-800 @else bg-gray-100 @endif">{{ $preAuthorization->status }}</span></dd></div>
                </dl>
                @if($preAuthorization->notes)
                <div class="mt-4"><dt class="text-sm font-medium text-gray-500">Notas</dt><dd class="mt-1 text-sm text-gray-900">{{ $preAuthorization->notes }}</dd></div>
                @endif
                <div class="mt-6 p-4 bg-gray-50 rounded-md">
                    <p class="text-sm font-medium text-gray-700 mb-2">Código QR:</p>
                    <div class="flex items-center space-x-2">
                        <code class="text-sm bg-white px-3 py-2 rounded border">{{ $preAuthorization->qr_code }}</code>
                        <button onclick="navigator.clipboard.writeText('{{ $preAuthorization->qr_code }}')" class="text-xs text-indigo-600 hover:text-indigo-900">Copiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
