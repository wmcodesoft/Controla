<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Vigilancia</p>
                    <h2 class="text-xl font-bold text-white">Nueva Minuta</h2>
                </div>
                <a href="{{ route('access.guard_logs.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="-mt-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Registrar Novedad</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Complete los campos para registrar una nueva minuta de vigilancia</p>
                </div>
                <div class="px-6 py-5">
                    <form method="POST" action="{{ route('access.guard_logs.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <select name="location_id" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha/Hora</label>
                                <input type="datetime-local" name="log_time" value="{{ old('log_time', date('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select name="type" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="general">📋 General</option>
                                    <option value="novedad">🔶 Novedad</option>
                                    <option value="turno">🔄 Cambio de Turno</option>
                                    <option value="incidente">🚨 Incidente</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Turno</label>
                                <select name="shift_type" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="diurno">☀️ Diurno</option>
                                    <option value="nocturno">🌙 Nocturno</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describa la novedad, incidente o novedad ocurrida durante el turno..." required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('access.guard_logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg font-semibold text-xs text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Guardar Minuta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>