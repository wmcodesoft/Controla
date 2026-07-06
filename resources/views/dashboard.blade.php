<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('access.dashboard')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('access.dashboard') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-indigo-50 transition">
                    <h3 class="text-lg font-semibold text-gray-900">Control de Acceso</h3>
                    <p class="mt-2 text-sm text-gray-600">Gestión de visitantes, ingresos, salidas, correspondencia y más.</p>
                </a>
                <a href="{{ route('access.logs.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-green-50 transition">
                    <h3 class="text-lg font-semibold text-gray-900">Ingreso/Salida</h3>
                    <p class="mt-2 text-sm text-gray-600">Registro rápido de ingresos y salidas de visitantes.</p>
                </a>
                <a href="{{ route('access.reports.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-blue-50 transition">
                    <h3 class="text-lg font-semibold text-gray-900">Reportes</h3>
                    <p class="mt-2 text-sm text-gray-600">Estadísticas y filtros de acceso.</p>
                </a>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            @endcan
        </div>
    </div>
</x-app-layout>
