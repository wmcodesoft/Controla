<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Ingreso</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Ingreso</label>
                    <div class="flex space-x-4" x-data="{ type: 'visitor' }">
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="type" value="visitor" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Visitante (peatonal)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="type" value="visitor_vehicle" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Visitante (vehicular)</span>
                        </label>
                    </div>
                </div>

                {{-- Formulario Visitante Peatonal --}}
                <form method="POST" action="{{ route('access.logs.entry.store') }}" x-show="type === 'visitor'" x-data="entryForm()">
                    @csrf
                    <input type="hidden" name="access_type" value="visitor">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Visitante</label>
                        <input type="text" x-model="search" @input.debounce="searchVisitor()" placeholder="Buscar por documento o nombre..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="hidden" name="visitor_id" x-model="selectedVisitorId">
                        <div x-show="searchResults.length > 0 && !selectedVisitorId" class="mt-1 bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                            <template x-for="v in searchResults" :key="v.id">
                                <div @click="selectVisitor(v)" class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-sm">
                                    <span x-text="v.document_type + ' ' + v.document_number"></span> - <span x-text="v.first_name + ' ' + v.last_name"></span>
                                    <span x-show="v.company" x-text="'(' + v.company + ')'" class="text-gray-500"></span>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedVisitor" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm font-medium" x-text="selectedVisitorLabel"></p>
                            <button type="button" @click="clearSelection()" class="text-xs text-red-600 hover:text-red-800">Cambiar visitante</button>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('access.visitors.create') }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">+ Crear nuevo visitante</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Anfitrión</label>
                            <select name="host_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($hosts as $host)
                                <option value="{{ $host->id }}">{{ $host->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                            <label class="block text-sm font-medium text-gray-700">Apartamento Destino</label>
                            <select name="housing_unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">No aplica</option>
                                @foreach($housingUnits as $hu)
                                <option value="{{ $hu->id }}">{{ $hu->full_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Propósito</label>
                            <input type="text" name="purpose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Reunión, entrega, etc.">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Empresa a Visitar</label>
                            <input type="text" name="company_visited" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Temperatura (°C)</label>
                            <input type="number" step="0.1" name="screening_temp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="36.5">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('access.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Registrar Ingreso</button>
                    </div>
                </form>

                {{-- Formulario Visitante Vehicular --}}
                <form method="POST" action="{{ route('access.logs.entry.store') }}" x-show="type === 'visitor_vehicle'" x-data="visitorVehicleForm()">
                    @csrf
                    <input type="hidden" name="access_type" value="visitor_vehicle">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Visitante</label>
                        <input type="text" x-model="vsearch" @input.debounce="searchVisitor()" placeholder="Buscar por documento o nombre..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="hidden" name="visitor_id" x-model="selectedVisitorId">
                        <div x-show="visitorResults.length > 0 && !selectedVisitorId" class="mt-1 bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                            <template x-for="v in visitorResults" :key="v.id">
                                <div @click="selectVisitor(v)" class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-sm">
                                    <span x-text="v.document_type + ' ' + v.document_number"></span> - <span x-text="v.first_name + ' ' + v.last_name"></span>
                                    <span x-show="v.company" x-text="'(' + v.company + ')'" class="text-gray-500"></span>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedVisitor" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm font-medium" x-text="selectedVisitorLabel"></p>
                            <button type="button" @click="clearVisitor()" class="text-xs text-red-600 hover:text-red-800">Cambiar visitante</button>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('access.visitors.create') }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">+ Crear nuevo visitante</a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Vehículo por Placa (opcional)</label>
                        <input type="text" x-model="psearch" @input.debounce="searchPlate()" placeholder="Buscar por placa..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="hidden" name="vehicle_id" x-model="selectedVehicleId">
                        <div x-show="plateResults.length > 0 && !selectedVehicleId" class="mt-1 bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                            <template x-for="v in plateResults" :key="v.id">
                                <div @click="selectVehicle(v)" class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-sm">
                                    <span class="font-medium" x-text="v.plate"></span>
                                    <span x-text="v.brand + ' ' + v.model + ' (' + v.color + ')'" class="text-gray-500"></span>
                                    <span x-show="v.visitor" x-text="'- ' + v.visitor.first_name + ' ' + v.visitor.last_name" class="text-gray-500"></span>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedVehicle" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm font-medium" x-text="selectedPlateLabel"></p>
                            <button type="button" @click="clearVehicle()" class="text-xs text-red-600 hover:text-red-800">Cambiar vehículo</button>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('access.vehicles.create') }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">+ Registrar nuevo vehículo</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Anfitrión</label>
                            <select name="host_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($hosts as $host)
                                <option value="{{ $host->id }}">{{ $host->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                            <label class="block text-sm font-medium text-gray-700">Apartamento Destino</label>
                            <select name="housing_unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">No aplica</option>
                                @foreach($housingUnits as $hu)
                                <option value="{{ $hu->id }}">{{ $hu->full_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Propósito</label>
                            <input type="text" name="purpose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Reunión, entrega, etc.">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Empresa a Visitar</label>
                            <input type="text" name="company_visited" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Temperatura (°C)</label>
                            <input type="number" step="0.1" name="screening_temp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="36.5">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('access.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Registrar Ingreso</button>
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

        function visitorVehicleForm() {
            return {
                vsearch: '',
                visitorResults: [],
                selectedVisitorId: null,
                selectedVisitor: null,
                psearch: '',
                plateResults: [],
                selectedVehicleId: null,
                selectedVehicle: null,
                get selectedVisitorLabel() {
                    if (!this.selectedVisitor) return '';
                    return this.selectedVisitor.document_type + ' ' + this.selectedVisitor.document_number + ' - ' + this.selectedVisitor.first_name + ' ' + this.selectedVisitor.last_name;
                },
                get selectedPlateLabel() {
                    if (!this.selectedVehicle) return '';
                    let label = this.selectedVehicle.plate;
                    if (this.selectedVehicle.visitor) label += ' - ' + this.selectedVehicle.visitor.first_name + ' ' + this.selectedVehicle.visitor.last_name;
                    return label;
                },
                async searchVisitor() {
                    if (this.vsearch.length < 2) { this.visitorResults = []; return; }
                    try {
                        const res = await fetch('{{ route("access.visitors.search.json") }}?q=' + encodeURIComponent(this.vsearch));
                        this.visitorResults = await res.json();
                    } catch(e) { this.visitorResults = []; }
                },
                selectVisitor(v) {
                    this.selectedVisitor = v;
                    this.selectedVisitorId = v.id;
                    this.vsearch = v.document_number + ' - ' + v.first_name + ' ' + v.last_name;
                    this.visitorResults = [];
                },
                clearVisitor() {
                    this.selectedVisitor = null;
                    this.selectedVisitorId = null;
                    this.vsearch = '';
                    this.visitorResults = [];
                },
                async searchPlate() {
                    if (this.psearch.length < 1) { this.plateResults = []; return; }
                    try {
                        const res = await fetch('{{ route("access.vehicles.search.json") }}?q=' + encodeURIComponent(this.psearch));
                        this.plateResults = await res.json();
                    } catch(e) { this.plateResults = []; }
                },
                selectVehicle(v) {
                    this.selectedVehicle = v;
                    this.selectedVehicleId = v.id;
                    this.psearch = v.plate;
                    this.plateResults = [];
                },
                clearVehicle() {
                    this.selectedVehicle = null;
                    this.selectedVehicleId = null;
                    this.psearch = '';
                    this.plateResults = [];
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
