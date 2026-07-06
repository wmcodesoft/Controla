<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Ingreso Vehicular</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('access.vehicle_access.entry.store') }}" x-data="vehicleForm()">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Vehículo por Placa</label>
                        <input type="text" x-model="search" @input.debounce="searchVehicle()" placeholder="Ingrese la placa..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="hidden" name="vehicle_id" x-model="selectedVehicleId">
                        <input type="hidden" name="user_id" x-model="selectedUserId">
                        <input type="hidden" name="resident_id" x-model="selectedResidentId">
                        <div x-show="searchResults.length > 0 && !selectedVehicleId" class="mt-1 bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                            <template x-for="v in searchResults" :key="v.id">
                                <div @click="selectVehicle(v)" class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-sm">
                                    <span class="font-medium" x-text="v.plate"></span>
                                    <span x-text="v.brand + ' ' + v.model + ' (' + v.color + ')'" class="text-gray-600"></span>
                                    <span x-show="v.owner" x-text="'- ' + v.owner.name" class="text-gray-500"></span>
                                    <span x-show="v.resident" x-text="'- ' + v.resident.first_name + ' ' + v.resident.last_name" class="text-gray-500"></span>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedVehicle" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm font-medium" x-text="selectedVehicleLabel"></p>
                            <button type="button" @click="clearSelection()" class="text-xs text-red-600 hover:text-red-800">Cambiar vehículo</button>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('access.vehicles.create') }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">+ Registrar nuevo vehículo</a>
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
                            <label class="block text-sm font-medium text-gray-700">Propósito</label>
                            <input type="text" name="purpose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ingreso a parqueadero, carga, etc.">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('access.vehicle_access.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Registrar Ingreso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        function vehicleForm() {
            return {
                search: '',
                searchResults: [],
                selectedVehicleId: null,
                selectedUserId: null,
                selectedResidentId: null,
                selectedVehicle: null,
                get selectedVehicleLabel() {
                    if (!this.selectedVehicle) return '';
                    let label = this.selectedVehicle.plate;
                    if (this.selectedVehicle.owner) label += ' - ' + this.selectedVehicle.owner.name;
                    else if (this.selectedVehicle.resident) label += ' - ' + this.selectedVehicle.resident.first_name + ' ' + this.selectedVehicle.resident.last_name;
                    return label;
                },
                async searchVehicle() {
                    if (this.search.length < 1) { this.searchResults = []; return; }
                    try {
                        const res = await fetch('{{ route("access.vehicle_access.search") }}?q=' + encodeURIComponent(this.search));
                        this.searchResults = await res.json();
                    } catch(e) { this.searchResults = []; }
                },
                selectVehicle(v) {
                    this.selectedVehicle = v;
                    this.selectedVehicleId = v.id;
                    this.selectedUserId = v.user_id;
                    this.selectedResidentId = v.resident_id;
                    let ownerName = v.owner ? v.owner.name : (v.resident ? v.resident.first_name + ' ' + v.resident.last_name : '');
                    this.search = v.plate + (ownerName ? ' - ' + ownerName : '');
                    this.searchResults = [];
                },
                clearSelection() {
                    this.selectedVehicle = null;
                    this.selectedVehicleId = null;
                    this.selectedUserId = null;
                    this.selectedResidentId = null;
                    this.search = '';
                    this.searchResults = [];
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
