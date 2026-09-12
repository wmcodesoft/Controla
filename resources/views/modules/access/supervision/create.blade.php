<x-access-layout>
    <div class="-mt-6 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pt-6 pb-8 bg-gradient-to-r from-indigo-900 to-slate-900 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-300">Supervisión</p>
                <h2 class="text-xl font-bold text-white">Nueva Supervisión</h2>
            </div>
            <a href="{{ route('access.supervision.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8" x-data="supervisionForm()">
        @php($activeSupervisor = session('supervision.supervisor_name'))
        @php($locations = $locations ?? collect())
        @php($firstLocation = $locations->first())
        @php($isNight = now()->hour < 6 || now()->hour >= 18)
        <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-lg font-semibold text-white">Registrar Supervisión</h3>
                <p class="text-sm text-slate-500 mt-0.5">Complete la descripción y confirme el registro</p>
            </div>
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('access.supervision.store') }}">
                    @csrf

                    @if($errors->any())
                    <div class="mb-5 rounded-lg bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <div class="mb-5 flex items-center gap-3 bg-indigo-900/40 border border-indigo-700 rounded-lg p-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold flex-shrink-0">{{ strtoupper(substr($activeSupervisor ?? 'S', 0, 2)) }}</div>
                        <div>
                            <p class="text-xs font-medium text-indigo-300 uppercase tracking-wider">Supervisor</p>
                            <p class="text-sm font-semibold text-white">{{ $activeSupervisor ?? '—' }}</p>
                        </div>
                        <input type="hidden" name="supervisor_name" value="{{ $activeSupervisor }}">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div class="bg-slate-950 rounded-lg p-3 border border-slate-700">
                            <p class="text-xs text-slate-500 mb-1">Ubicación</p>
                            <p class="text-sm font-medium text-white">{{ $firstLocation->name ?? '—' }}</p>
                        </div>
                        <div class="bg-slate-950 rounded-lg p-3 border border-slate-700">
                            <p class="text-xs text-slate-500 mb-1">Fecha / Hora</p>
                            <p class="text-sm font-medium text-white">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="bg-slate-950 rounded-lg p-3 border border-slate-700">
                            <p class="text-xs text-slate-500 mb-1">Tipo</p>
                            <p class="text-sm font-medium text-white">Ronda de rutina</p>
                        </div>
                        <div class="bg-slate-950 rounded-lg p-3 border border-slate-700">
                            <p class="text-xs text-slate-500 mb-1">Turno</p>
                            <p class="text-sm font-medium text-white">{{ $isNight ? 'Nocturno' : 'Diurno' }}</p>
                        </div>
                    </div>

                    <input type="hidden" name="location_id" value="{{ $firstLocation->id }}">
                    <input type="hidden" name="log_date" value="{{ now()->format('Y-m-d\TH:i') }}">
                    <input type="hidden" name="type" value="rutina">
                    <input type="hidden" name="shift_type" value="{{ $isNight ? 'nocturno' : 'diurno' }}">
                    <input type="hidden" name="latitude" :value="geoLat">
                    <input type="hidden" name="longitude" :value="geoLng">

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-300">Descripción</label>
                        <textarea name="description" rows="4" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describa los hallazgos, observaciones o novedades de la supervisión..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="bg-emerald-900/30 rounded-lg p-4 border border-emerald-700">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="signed" value="1" class="mt-0.5 rounded bg-slate-950 border-emerald-600 text-emerald-500 focus:ring-emerald-500" required>
                            <div>
                                <p class="text-sm font-medium text-emerald-200">Confirmo que la información registrada es verídica</p>
                                <p class="text-xs text-emerald-400">Firma digital — el sistema registrará su identidad y hora de confirmación.</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-800">
                        <a href="{{ route('access.supervision.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg font-semibold text-xs text-slate-300 hover:bg-slate-700 transition-colors">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Guardar Supervisión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    function supervisionForm() {
        return {
            geoLat: '',
            geoLng: '',
            geoCaptured: false,
            geoError: '',
            init() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            this.geoLat = pos.coords.latitude.toFixed(7);
                            this.geoLng = pos.coords.longitude.toFixed(7);
                            this.geoCaptured = true;
                        },
                        (err) => {
                            switch(err.code) {
                                case err.PERMISSION_DENIED: this.geoError = 'Permiso denegado. Active la ubicación.'; break;
                                case err.POSITION_UNAVAILABLE: this.geoError = 'Ubicación no disponible.'; break;
                                case err.TIMEOUT: this.geoError = 'Tiempo de espera agotado.'; break;
                            }
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                }
            }
        }
    }
</script>
@endpush
</x-access-layout>