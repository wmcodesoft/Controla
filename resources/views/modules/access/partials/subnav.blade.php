<div class="py-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @can('access.dashboard')
        <a href="{{ route('access.dashboard') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-indigo-300 p-4 transition-all duration-200 {{ request()->routeIs('access.dashboard') ? 'ring-2 ring-indigo-500 border-indigo-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700">Dashboard</p>
                    <p class="text-xs text-gray-500">Resumen general del sistema</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.register.entry')
        <a href="{{ route('access.logs.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-green-300 p-4 transition-all duration-200 {{ request()->routeIs('access.logs.*') ? 'ring-2 ring-green-500 border-green-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-green-700">Ingreso/Salida</p>
                    <p class="text-xs text-gray-500">Registrar entrada y salida de visitantes</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.visitors')
        <a href="{{ route('access.visitors.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-300 p-4 transition-all duration-200 {{ request()->routeIs('access.visitors.*') ? 'ring-2 ring-blue-500 border-blue-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">Visitantes</p>
                    <p class="text-xs text-gray-500">Administrar visitantes registrados</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.residents')
        <a href="{{ route('access.residents.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-teal-300 p-4 transition-all duration-200 {{ request()->routeIs('access.residents.*') ? 'ring-2 ring-teal-500 border-teal-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-teal-700">Residentes</p>
                    <p class="text-xs text-gray-500">Personas que viven en el conjunto</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.housing_units')
        <a href="{{ route('access.housing_units.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-orange-300 p-4 transition-all duration-200 {{ request()->routeIs('access.housing_units.*') ? 'ring-2 ring-orange-500 border-orange-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-orange-700">Apartamentos</p>
                    <p class="text-xs text-gray-500">Unidades de vivienda del conjunto</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.buildings')
        <a href="{{ route('access.buildings.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-gray-400 p-4 transition-all duration-200 {{ request()->routeIs('access.buildings.*') ? 'ring-2 ring-gray-500 border-gray-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700">Torres/Bloques</p>
                    <p class="text-xs text-gray-500">Edificios del conjunto residencial</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.vehicles')
        <a href="{{ route('access.vehicles.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-cyan-300 p-4 transition-all duration-200 {{ request()->routeIs('access.vehicles.*') ? 'ring-2 ring-cyan-500 border-cyan-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0V21h14v-5m0 0h3l2-4-2-4h-3l-2 4h-1"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-cyan-700">Vehículos</p>
                    <p class="text-xs text-gray-500">Registro de vehículos del sistema</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.vehicle_access')
        <a href="{{ route('access.vehicle_access.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-amber-300 p-4 transition-all duration-200 {{ request()->routeIs('access.vehicle_access.*') ? 'ring-2 ring-amber-500 border-amber-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-amber-700">Control Vehicular</p>
                    <p class="text-xs text-gray-500">Ingreso/salida de vehículos de residentes</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.pre_authorizations')
        <a href="{{ route('access.pre_authorizations.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-purple-300 p-4 transition-all duration-200 {{ request()->routeIs('access.pre_authorizations.*') ? 'ring-2 ring-purple-500 border-purple-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-purple-700">Pre-Autorizaciones</p>
                    <p class="text-xs text-gray-500">Autorizaciones anticipadas de ingreso</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.correspondence')
        <a href="{{ route('access.correspondence.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-pink-300 p-4 transition-all duration-200 {{ request()->routeIs('access.correspondence.*') ? 'ring-2 ring-pink-500 border-pink-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-pink-700">Correspondencia</p>
                    <p class="text-xs text-gray-500">Paquetes y correspondencia recibida</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.guard_logs')
        <a href="{{ route('access.guard_logs.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-yellow-300 p-4 transition-all duration-200 {{ request()->routeIs('access.guard_logs.*') ? 'ring-2 ring-yellow-500 border-yellow-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-yellow-700">Minutas</p>
                    <p class="text-xs text-gray-500">Novedades y reportes del guardia</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.manage.locations')
        <a href="{{ route('access.locations.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-sky-300 p-4 transition-all duration-200 {{ request()->routeIs('access.locations.*') ? 'ring-2 ring-sky-500 border-sky-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-sky-700">Ubicaciones</p>
                    <p class="text-xs text-gray-500">Puntos de acceso y porterías</p>
                </div>
            </div>
        </a>
        @endcan

        @can('access.view.reports')
        <a href="{{ route('access.reports.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-rose-300 p-4 transition-all duration-200 {{ request()->routeIs('access.reports.*') ? 'ring-2 ring-rose-500 border-rose-500' : '' }}">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-rose-700">Reportes</p>
                    <p class="text-xs text-gray-500">Estadísticas e informes del sistema</p>
                </div>
            </div>
        </a>
        @endcan
    </div>
</div>
