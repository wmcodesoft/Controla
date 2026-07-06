<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Correspondencia</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asignar a</label>
                    <div class="flex space-x-4" x-data="{ assignType: 'resident' }">
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="assignType" value="resident" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Residente (por nombre)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="assignType" value="apartment" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Apartamento/Casa</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="assignType" value="host" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Usuario del sistema</span>
                        </label>
                    </div>
                </div>

                <form method="POST" action="{{ route('access.correspondence.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Resident field --}}
                        <div x-show="assignType === 'resident'">
                            <label class="block text-sm font-medium text-gray-700">Residente</label>
                            <select name="resident_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($residents as $r)
                                <option value="{{ $r->id }}" {{ old('resident_id') == $r->id ? 'selected' : '' }}>{{ $r->full_name }} ({{ $r->document_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Housing Unit field --}}
                        <div x-show="assignType === 'apartment'">
                            <label class="block text-sm font-medium text-gray-700">Apartamento/Casa</label>
                            <select name="housing_unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($housingUnits as $hu)
                                <option value="{{ $hu->id }}" {{ old('housing_unit_id') == $hu->id ? 'selected' : '' }}>{{ $hu->full_label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Host field --}}
                        <div x-show="assignType === 'host'">
                            <label class="block text-sm font-medium text-gray-700">Destinatario (usuario)</label>
                            <select name="host_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($hosts as $host)
                                <option value="{{ $host->id }}" {{ old('host_id') == $host->id ? 'selected' : '' }}>{{ $host->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <select name="location_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Paquete</label>
                            <select name="package_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="documento">Documento</option>
                                <option value="sobre">Sobre</option>
                                <option value="caja">Caja</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transportadora</label>
                            <input type="text" name="carrier" value="{{ old('carrier') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Guía</label>
                            <input type="text" name="courier_guide" value="{{ old('courier_guide') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Visitante (quien dejó)</label>
                            <select name="visitor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">N/A</option>
                                @foreach($visitors as $v)
                                <option value="{{ $v->id }}" {{ old('visitor_id') == $v->id ? 'selected' : '' }}>{{ $v->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('access.correspondence.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
