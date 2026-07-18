<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Pre-Autorizaciones</p>
                    <h2 class="text-xl font-bold text-white">Nueva Pre-Autorización</h2>
                </div>
                <a href="{{ route('access.pre_authorizations.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="-mt-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Autorizar Ingreso</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Autorice el ingreso anticipado de un visitante</p>
                </div>
                <div class="px-6 py-5">
                    <form method="POST" action="{{ route('access.pre_authorizations.store') }}" x-data="entryForm()">
                        @csrf

                        <div class="bg-purple-50 rounded-xl p-4 mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Visitante</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="search" @input.debounce="searchVisitor()" placeholder="Buscar por documento o nombre..." class="block w-full pl-10 rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <input type="hidden" name="visitor_id" x-model="selectedVisitorId">
                            <div x-show="searchResults.length > 0 && !selectedVisitorId" class="mt-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden divide-y divide-gray-100">
                                <template x-for="v in searchResults" :key="v.id">
                                    <div @click="selectVisitor(v)" class="px-4 py-3 hover:bg-indigo-50 cursor-pointer flex items-center justify-between transition-colors">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" x-text="v.first_name + ' ' + v.last_name"></p>
                                            <p class="text-xs text-gray-500" x-text="v.document_type + ' ' + v.document_number"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-show="selectedVisitor" class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                                <p class="text-sm font-medium text-emerald-800" x-text="selectedVisitorLabel"></p>
                                <button type="button" @click="clearSelection()" class="text-xs text-red-600 hover:text-red-800 font-medium">Cambiar</button>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('access.visitors.create') }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Crear nuevo visitante
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <select name="location_id" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccionar...</option>
                                    @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha Programada</label>
                                <input type="date" name="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hora (opcional)</label>
                                <input type="time" name="scheduled_time" value="{{ old('scheduled_time') }}" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Notas</label>
                            <textarea name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Información adicional para el guardia...">{{ old('notes') }}</textarea>
                        </div>
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('access.pre_authorizations.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg font-semibold text-xs text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Crear Pre-Autorización
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function entryForm() {
            return {
                search: '',
                searchResults: [],
                selectedVisitorId: null,
                selectedVisitor: null,
                get selectedVisitorLabel() {
                    if (!this.selectedVisitor) return '';
                    return this.selectedVisitor.document_type + ' ' + this.selectedVisitor.document_number + ' - ' + this.selectedVisitor.first_name + ' ' + this.selectedVisitor.last_name;
                },
                async searchVisitor() {
                    if (this.search.length < 2) { this.searchResults = []; return; }
                    try {
                        const res = await fetch('{{ route("access.visitors.search.json") }}?q=' + encodeURIComponent(this.search));
                        this.searchResults = await res.json();
                    } catch(e) { this.searchResults = []; }
                },
                selectVisitor(v) {
                    this.selectedVisitor = v;
                    this.selectedVisitorId = v.id;
                    this.search = v.document_number + ' - ' + v.first_name + ' ' + v.last_name;
                    this.searchResults = [];
                },
                clearSelection() {
                    this.selectedVisitor = null;
                    this.selectedVisitorId = null;
                    this.search = '';
                    this.searchResults = [];
                }
            }
        }
    </script>
    @endpush
</x-app-layout>