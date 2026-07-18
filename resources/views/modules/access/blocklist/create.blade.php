<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-slate-800 to-indigo-900 -mx-4 -mt-4 px-4 pt-4 pb-6 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-300">Seguridad</p>
                    <h2 class="text-xl font-bold text-white">Agregar a Lista de Bloqueo</h2>
                </div>
                <a href="{{ route('access.blocklist.index') }}" class="text-sm text-indigo-300 hover:text-white transition-colors">← Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="-mt-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Bloquear Ingreso</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Restrinja el acceso a una persona o vehículo</p>
                </div>
                <div class="px-6 py-5">
                    <form method="POST" action="{{ route('access.blocklist.store') }}" x-data="blocklistForm()">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo</label>
                            <select x-model="type" name="blockable_type" required class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-red-50 rounded-xl p-4 mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="search" @input.debounce="searchItems()" placeholder="Buscar por nombre, documento o placa..." class="block w-full pl-10 rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <input type="hidden" name="blockable_id" x-model="selectedId">
                            <div x-show="results.length > 0 && !selectedId" class="mt-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden divide-y divide-gray-100">
                                <template x-for="item in results" :key="item.id">
                                    <div @click="selectItem(item)" class="px-4 py-3 hover:bg-indigo-50 cursor-pointer flex items-center justify-between transition-colors">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" x-text="item.document_number || item.plate || item.first_name"></p>
                                            <p class="text-xs text-gray-500" x-text="item.first_name ? ' ' + item.last_name : (item.brand ? ' ' + item.brand + ' ' + item.model : '')"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-show="selectedItem" class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                                <p class="text-sm font-medium text-emerald-800" x-text="selectedLabel"></p>
                                <button type="button" @click="clearSelection()" class="text-xs text-red-600 hover:text-red-800 font-medium">Cambiar</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Razón del bloqueo</label>
                            <textarea name="reason" rows="3" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Ingreso denegado por comportamiento inapropiado"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Expira (opcional)</label>
                            <input type="datetime-local" name="expires_at" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Si no se especifica fecha, el bloqueo será permanente hasta que se remueva manualmente.</p>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('access.blocklist.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg font-semibold text-xs text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                Bloquear Ingreso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function blocklistForm() {
            return {
                type: 'visitor',
                search: '',
                results: [],
                selectedId: null,
                selectedItem: null,
                get selectedLabel() {
                    if (!this.selectedItem) return '';
                    return this.selectedItem.document_number || this.selectedItem.plate || (this.selectedItem.first_name + ' ' + this.selectedItem.last_name);
                },
                async searchItems() {
                    if (this.search.length < 2) { this.results = []; return; }
                    try {
                        const res = await fetch('{{ route("access.blocklist.search") }}?type=' + this.type + '&q=' + encodeURIComponent(this.search));
                        this.results = await res.json();
                    } catch(e) { this.results = []; }
                },
                selectItem(item) {
                    this.selectedItem = item;
                    this.selectedId = item.id;
                    this.search = this.selectedLabel;
                    this.results = [];
                },
                clearSelection() {
                    this.selectedItem = null;
                    this.selectedId = null;
                    this.search = '';
                    this.results = [];
                }
            }
        }
    </script>
    @endpush
</x-app-layout>