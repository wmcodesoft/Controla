<x-access-layout title="Estructuras">
    <div class="space-y-6">
        <div>
            <h3 class="text-sm font-semibold text-white">Estructura del conjunto</h3>
            <p class="text-xs text-slate-500 mt-1">
                @if ($client->structureType)
                    Tipo: <span class="text-indigo-300">{{ $client->structureType->name }}</span>
                @else
                    <span class="text-amber-300">Sin tipo asignado</span>
                @endif
                · {{ $tree->count() }} nodo{{ $tree->count() === 1 ? '' : 's' }} raíz
            </p>
        </div>

        <form method="GET" class="flex gap-2">
            <div class="flex-1 max-w-md relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" name="q" value="{{ $search ?? '' }}" placeholder="Buscar por nombre, código o tipo…"
                       class="w-full h-9 pl-9 pr-3 text-sm rounded-lg border border-slate-700 bg-slate-950 text-white placeholder:text-slate-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30">
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-700 transition-colors">
                Buscar
            </button>
            @if (($search ?? '') !== '')
                <a href="{{ route('access.structures.index') }}" class="inline-flex items-center px-3 py-2 text-sm text-slate-500 hover:text-slate-300 transition-colors">
                    Limpiar
                </a>
            @endif
        </form>

        @if ($tree->isEmpty())
            <div class="rounded-xl border border-slate-800 bg-slate-900 p-8 text-center">
                @if (($search ?? '') !== '')
                    <p class="text-sm text-slate-500">No se encontraron estructuras que coincidan con "{{ $search }}".</p>
                    <a href="{{ route('access.structures.index') }}" class="mt-2 inline-block text-xs text-indigo-400 hover:text-indigo-300">Ver todas</a>
                @else
                    <svg class="mx-auto h-12 w-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="mt-3 text-sm text-slate-500">No hay estructuras registradas.</p>
                @endif
            </div>
        @else
            <div class="rounded-xl border border-slate-800 bg-slate-900 p-4">
                <x-client.structure-tree :nodes="$tree" :census="$census" />
            </div>
        @endif
    </div>
</x-access-layout>
