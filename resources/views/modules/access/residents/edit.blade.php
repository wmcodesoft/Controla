<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Residente</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('access.residents.update', $resident) }}" x-data="residentForm()">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                            <select name="document_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['CC','CE','Pasaporte','NIT'] as $dt)
                                <option value="{{ $dt }}" {{ old('document_type', $resident->document_type) == $dt ? 'selected' : '' }}>{{ $dt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número Documento</label>
                            <input type="text" name="document_number" value="{{ old('document_number', $resident->document_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('document_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombres</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $resident->first_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $resident->last_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="phone" value="{{ old('phone', $resident->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $resident->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo Residente</label>
                            <select name="resident_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="propietario" {{ $resident->resident_type == 'propietario' ? 'selected' : '' }}>Propietario</option>
                                <option value="inquilino" {{ $resident->resident_type == 'inquilino' ? 'selected' : '' }}>Inquilino</option>
                                <option value="familiar" {{ $resident->resident_type == 'familiar' ? 'selected' : '' }}>Familiar</option>
                                <option value="empleado_domestico" {{ $resident->resident_type == 'empleado_domestico' ? 'selected' : '' }}>Empleado Doméstico</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Activo</label>
                            <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" {{ old('is_active', $resident->is_active) ? 'selected' : '' }}>Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Asignación de Vivienda</h3>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Seleccionar Apartamento(s)</label>
                            <select name="housing_units[]" multiple x-model="selectedUnitIds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" size="5">
                                <template x-for="unit in housingUnits" :key="unit.id">
                                    <option :value="unit.id" x-text="unit.building_name + ' - ' + unit.unit_number + ' (' + unit.type + ')'"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vivienda Principal</label>
                            <select name="primary_unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Ninguna</option>
                                <template x-for="unit in selectedUnits" :key="unit.id">
                                    <option :value="unit.id" x-text="unit.building_name + ' - ' + unit.unit_number" :selected="String(unit.id) === primaryUnitId"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $resident->notes) }}</textarea>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('access.residents.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function residentForm() {
            return {
                housingUnits: @json($housingUnitsData),
                selectedUnitIds: @json($residentUnitIds),
                primaryUnitId: @json($primaryUnitId),
                get selectedUnits() {
                    return this.housingUnits.filter(u => this.selectedUnitIds.includes(String(u.id)));
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
