<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agregar a Lista de Bloqueo</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('access.blocklist.store') }}" x-data="blocklistForm()">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select x-model="type" name="blockable_type" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" x-model="search" @input.debounce="searchItems()" placeholder="Buscar por nombre, documento o placa..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="hidden" name="blockable_id" x-model="selectedId">
                        <div x-show="results.length > 0 && !selectedId" class="mt-1 bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                            <template x-for="item in results" :key="item.id">
                                <div @click="selectItem(item)" class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-sm">
                                    <span x-text="item.document_number || item.plate || item.first_name"></span>
                                    <span x-text="item.first_name ? ' ' + item.last_name : (item.brand ? ' ' + item.brand + ' ' + item.model : '')" class="text-gray-500"></span>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedItem" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm font-medium" x-text="selectedLabel"></p>
                            <button type="button" @click="clearSelection()" class="text-xs text-red-600 hover:text-red-800">Cambiar</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Razón del bloqueo</label>
                        <textarea name="reason" rows="2" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Ingreso denegado por comportamiento inapropiado"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Expira (opcional)</label>
                        <input type="datetime-local" name="expires_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('access.blocklist.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">Bloquear</button>
                    </div>
                </form>
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
