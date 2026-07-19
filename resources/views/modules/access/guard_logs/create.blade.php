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
                <div class="px-6 py-5" x-data="geoCapture()">
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

                        {{-- Geolocation section --}}
                        <div class="mt-5 bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-sm font-medium text-gray-700">Geolocalización</span>
                                </div>
                                <button @click="capture()" type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition-colors" x-text="captured ? 'Capturada' : 'Capturar ubicación'"></button>
                            </div>
                            <div class="mt-2 text-xs text-gray-500" x-show="!captured && !error && !loading">
                                La ubicación se usará para verificar la presencia del guardia en el sitio.
                            </div>
                            <div class="mt-2 text-xs text-emerald-600" x-show="captured" x-cloak>
                                <span class="font-medium">✓ Ubicación capturada:</span>
                                <span x-text="`${lat}, ${lng}`"></span>
                            </div>
                            <div class="mt-2 text-xs text-red-600" x-show="error" x-cloak x-text="error"></div>
                            <div class="mt-2 text-xs text-gray-400" x-show="loading" x-cloak>Obteniendo ubicación...</div>
                            <input type="hidden" name="latitude" x-model="lat">
                            <input type="hidden" name="longitude" x-model="lng">
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describa la novedad, incidente o novedad ocurrida durante el turno..." required>{{ old('description') }}</textarea>
                        </div>

                        {{-- Signature / double factor --}}
                        <div class="mt-5 bg-amber-50 rounded-lg p-4 border border-amber-200">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="signed" value="1" class="mt-0.5 rounded border-amber-300 text-amber-600 focus:ring-amber-500" required>
                                <div>
                                    <p class="text-sm font-medium text-amber-900">Confirmo que la información registrada es verídica</p>
                                    <p class="text-xs text-amber-600">Firma digital — doble factor de autenticación. Al marcar, el sistema registrará su identidad y hora de confirmación.</p>
                                </div>
                            </label>
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

@push('scripts')
<script>
    function geoCapture() {
        return {
            lat: '{{ old('latitude') }}',
            lng: '{{ old('longitude') }}',
            captured: !!'{{ old('latitude') }}',
            loading: false,
            error: '',
            capture() {
                if (!navigator.geolocation) {
                    this.error = 'Geolocalización no disponible en este navegador.';
                    return;
                }
                this.loading = true;
                this.error = '';
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.lat = pos.coords.latitude.toFixed(7);
                        this.lng = pos.coords.longitude.toFixed(7);
                        this.captured = true;
                        this.loading = false;
                    },
                    (err) => {
                        this.loading = false;
                        switch(err.code) {
                            case err.PERMISSION_DENIED:
                                this.error = 'Permiso denegado. Active la ubicación en su navegador.';
                                break;
                            case err.POSITION_UNAVAILABLE:
                                this.error = 'Ubicación no disponible.';
                                break;
                            case err.TIMEOUT:
                                this.error = 'Tiempo de espera agotado.';
                                break;
                        }
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            }
        }
    }
</script>
@endpush
            </div>
        </div>
    </div>
</x-app-layout>