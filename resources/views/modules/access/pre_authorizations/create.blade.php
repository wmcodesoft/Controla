<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva Pre-Autorización</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('access.pre_authorizations.store') }}" x-data="entryForm()">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visitante</label>
                        <input type="text" x-model="search" @input.debounce="searchVisitor()" placeholder="Buscar por documento o nombre..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="hidden" name="visitor_id" x-model="selectedVisitorId">
                        <div x-show="searchResults.length > 0 && !selectedVisitorId" class="mt-1 bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                            <template x-for="v in searchResults" :key="v.id">
                                <div @click="selectVisitor(v)" class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-sm">
                                    <span x-text="v.document_type + ' ' + v.document_number"></span> - <span x-text="v.first_name + ' ' + v.last_name"></span>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedVisitor" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm font-medium" x-text="selectedVisitorLabel"></p>
                            <button type="button" @click="clearSelection()" class="text-xs text-red-600 hover:text-red-800">Cambiar</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <select name="location_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Programada</label>
                            <input type="date" name="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora (opcional)</label>
                            <input type="time" name="scheduled_time" value="{{ old('scheduled_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('access.pre_authorizations.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Crear Pre-Autorización</button>
                    </div>
                </form>
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
