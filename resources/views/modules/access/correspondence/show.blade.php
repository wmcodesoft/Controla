<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Correspondencia</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $destinatario = $correspondence->resident?->full_name ?? $correspondence->housingUnit?->full_label ?? $correspondence->host?->name ?? '-';
                    @endphp
                    <div><dt class="text-sm font-medium text-gray-500">Destinatario</dt><dd class="mt-1 text-sm text-gray-900">{{ $destinatario }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Tipo</dt><dd class="mt-1 text-sm text-gray-900">{{ ucfirst($correspondence->package_type) }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Transportadora</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->carrier ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Guía</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->courier_guide ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Recibido por</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->receiver?->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Recibido el</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->received_at->format('d/m/Y H:i') }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Estado</dt><dd class="mt-1 text-sm"><span class="px-2 py-1 text-xs rounded-full {{ $correspondence->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $correspondence->status == 'pending' ? 'Pendiente' : 'Entregado' }}</span></dd></div>
                    @if($correspondence->delivered_at)
                    <div><dt class="text-sm font-medium text-gray-500">Entregado por</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->deliverer?->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Entregado el</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->delivered_at->format('d/m/Y H:i') }}</dd></div>
                    @endif
                </dl>
                @if($correspondence->notes)
                <div class="mt-4"><dt class="text-sm font-medium text-gray-500">Notas</dt><dd class="mt-1 text-sm text-gray-900">{{ $correspondence->notes }}</dd></div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
