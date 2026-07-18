<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Vigilancia</p>
                    <h2 class="text-xl font-bold text-white">Minuta de Vigilancia</h2>
                </div>
                <a href="{{ route('access.guard_logs.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="-mt-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white text-lg font-bold">
                            {{ strtoupper(substr($guardLog->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $guardLog->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $guardLog->user->email }}</p>
                        </div>
                        <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ring-1
                            @if($guardLog->type == 'incidente') bg-red-50 text-red-700 ring-red-600/20
                            @elseif($guardLog->type == 'novedad') bg-amber-50 text-amber-700 ring-amber-600/20
                            @elseif($guardLog->type == 'turno') bg-blue-50 text-blue-700 ring-blue-600/20
                            @else bg-gray-50 text-gray-700 ring-gray-600/20 @endif">
                            {{ ucfirst($guardLog->type) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $guardLog->location->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $guardLog->log_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Turno</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ ucfirst($guardLog->shift_type) }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Descripción</p>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900 whitespace-pre-wrap leading-relaxed">{{ $guardLog->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <form action="{{ route('access.guard_logs.destroy', $guardLog) }}" method="POST" onsubmit="return confirm('¿Eliminar esta minuta?')">
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