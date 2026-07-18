<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Correspondencia</p>
                    <h2 class="text-xl font-bold text-white">Detalle de Correspondencia</h2>
                </div>
                <a href="{{ route('access.correspondence.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
            </div>
        </div>
    </x-slot>

    @php
        $destinatario = $correspondence->resident?->full_name ?? $correspondence->housingUnit?->full_label ?? $correspondence->host?->name ?? '-';
    @endphp

    <div class="-mt-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-500 to-pink-700 flex items-center justify-center text-white text-lg font-bold">
                            {{ strtoupper(substr($destinatario, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $destinatario }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1
                                @if($correspondence->package_type == 'caja') bg-orange-50 text-orange-700 ring-orange-600/20
                                @elseif($correspondence->package_type == 'sobre') bg-blue-50 text-blue-700 ring-blue-600/20
                                @else bg-gray-50 text-gray-700 ring-gray-600/20 @endif">
                                {{ ucfirst($correspondence->package_type) }}
                            </span>
                        </div>
                        <span class="ml-auto inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium ring-1
                            {{ $correspondence->status == 'pending' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' }}">
                            @if($correspondence->status == 'pending')
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                Pendiente
                            @else
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Entregado
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transportadora</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $correspondence->carrier ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Guía</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $correspondence->courier_guide ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Recibido por</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $correspondence->receiver?->name ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Recibido el</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $correspondence->received_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($correspondence->delivered_at)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Entregado por</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $correspondence->deliverer?->name ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Entregado el</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $correspondence->delivered_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($correspondence->notes)
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Notas</p>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-900">{{ $correspondence->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    @if($correspondence->status == 'pending')
                    <form action="{{ route('access.correspondence.deliver', $correspondence) }}" method="POST" onsubmit="return confirm('¿Marcar como entregado?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-emerald-700 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Marcar como Entregado
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('access.correspondence.destroy', $correspondence) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-red-700 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>