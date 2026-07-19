<div
    x-data="{ open: false, openPanic: false, panicLat: '', panicLng: '', initPanic() { if (navigator.geolocation) { navigator.geolocation.getCurrentPosition((p) => { this.panicLat = p.coords.latitude.toFixed(7); this.panicLng = p.coords.longitude.toFixed(7); }, () => {}, { timeout: 5000 }); } } }"
    x-init="initPanic()"
    x-cloak
    @keydown.escape="open = false"
    class="relative z-40"
>
    {{-- Overlay --}}
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity"
        x-transition.opacity
    ></div>

    {{-- Trigger button --}}
    <button
        @click="open = !open"
        class="fixed left-0 top-1/2 -translate-y-1/2 z-50 w-10 h-20 bg-gradient-to-br from-purple-600 to-indigo-700 hover:from-purple-500 hover:to-indigo-600 rounded-r-xl shadow-lg flex items-center justify-center transition-all duration-200 group"
        x-bind:class="{ 'rounded-l-xl': open }"
        title="Accesos rápidos"
    >
        <svg class="w-5 h-5 text-white transition-transform duration-300" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
            Accesos rápidos
        </span>
    </button>

    {{-- Sidebar panel --}}
    <div
        x-show="open"
        x-transition:enter="transition-transform duration-300 ease-out"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 h-full w-64 bg-white shadow-2xl border-r border-gray-200 overflow-y-auto"
    >
        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-700 to-indigo-800 px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-purple-200">Accesos Rápidos</p>
                    <p class="text-sm font-bold text-white">Portería</p>
                </div>
                <button @click="open = false" class="text-purple-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="p-3 space-y-1.5">
            @can('access.register.entry')
            <a href="{{ route('access.logs.entry') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-emerald-700">Ingreso Peatonal</p>
                    <p class="text-xs text-gray-400 group-hover:text-emerald-500">Registrar visitante</p>
                </div>
            </a>
            @endcan

            @can('access.manage.vehicle_access')
            <a href="{{ route('access.vehicle_access.entry') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-cyan-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:bg-cyan-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0V21h14v-5m0 0h3l2-4-2-4h-3l-2 4h-1"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-cyan-700">Ingreso Vehicular</p>
                    <p class="text-xs text-gray-400 group-hover:text-cyan-500">Registrar vehículo</p>
                </div>
            </a>
            @endcan

            @can('access.register.exit')
            <a href="{{ route('access.logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-red-700">Registrar Salida</p>
                    <p class="text-xs text-gray-400 group-hover:text-red-500">Marcar salida</p>
                </div>
            </a>
            @endcan

            <hr class="my-2 border-gray-100">

            @can('access.register.entry')
            <a href="{{ route('access.logs.index') }}#dentro" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-indigo-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-indigo-700">Personas Dentro</p>
                    <p class="text-xs text-gray-400 group-hover:text-indigo-500">Quiénes están adentro</p>
                </div>
            </a>
            @endcan

            @can('access.manage.guard_logs')
            <a href="{{ route('access.guard_logs.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-amber-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-amber-700">Nueva Minuta</p>
                    <p class="text-xs text-gray-400 group-hover:text-amber-500">Registrar novedad</p>
                </div>
            </a>
            @endcan

            @can('access.manage.pre_authorizations')
            <a href="{{ route('access.pre_authorizations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-purple-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-purple-700">Pre-Autorizaciones</p>
                    <p class="text-xs text-gray-400 group-hover:text-purple-500">Autorizaciones activas</p>
                </div>
            </a>
            @endcan

            @can('access.manage.correspondence')
            <a href="{{ route('access.correspondence.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-pink-50 group transition-all duration-150">
                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-pink-700">Registrar Paquete</p>
                    <p class="text-xs text-gray-400 group-hover:text-pink-500">Correspondencia recibida</p>
                </div>
            </a>
            @endcan
        </div>

        {{-- Panic button --}}
        <div class="px-3 pb-2">
            <hr class="my-2 border-gray-100">
            <button
                @click="openPanic = true"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 group transition-all duration-150 border border-red-200"
            >
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-red-800">Botón de Pánico</p>
                    <p class="text-xs text-red-500">Alerta inmediata</p>
                </div>
            </button>
        </div>

        {{-- Panic confirmation modal --}}
        <div
            x-show="openPanic"
            x-cloak
            @keydown.escape="openPanic = false"
            class="fixed inset-0 z-[60] overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="openPanic" x-transition.opacity @click="openPanic = false" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
                <div x-show="openPanic" x-transition:enter="transition-transform duration-300 ease-out" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>
                    <div class="bg-gradient-to-r from-red-600 to-rose-700 px-6 py-5 text-center">
                        <div class="w-16 h-16 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        <p class="text-lg font-bold text-white">¿Generar alerta de pánico?</p>
                        <p class="text-sm text-red-200 mt-1">Esta acción notificará al personal de seguridad.</p>
                    </div>
                    <form method="POST" action="{{ route('access.guard_logs.panic') }}" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                            <select name="location_id" required class="w-full rounded-lg border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @foreach(\App\Models\Location::where('is_active', true)->get() as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la emergencia</label>
                            <textarea name="description" rows="2" required class="w-full rounded-lg border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Describa la situación..."></textarea>
                        </div>
                        <input type="hidden" name="latitude" x-bind:value="panicLat">
                        <input type="hidden" name="longitude" x-bind:value="panicLng">
                        <div class="flex gap-3">
                            <button type="button" @click="openPanic = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                            <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm inline-flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                Activar Pánico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-gray-100 bg-gray-50">
            <button @click="open = false" class="w-full text-center text-xs text-gray-400 hover:text-gray-600 transition-colors py-1">
                Cerrar panel
            </button>
        </div>
    </div>
</div>