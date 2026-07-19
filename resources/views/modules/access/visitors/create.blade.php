<x-access-layout>
    <div class="-mt-6 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pt-6 pb-8 bg-gradient-to-r from-slate-800 to-indigo-900 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-300">Control de Acceso</p>
                <h2 class="text-xl font-bold text-white">Nuevo Visitante</h2>
            </div>
        </div>
    </div>

    <div class="bg-slate-900 rounded-xl border border-slate-800 p-6">
        <form method="POST" action="{{ route('access.visitors.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300">Tipo Documento</label>
                    <select name="document_type" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach(['CC','NIT','CE','Pasaporte'] as $dt)
                        <option value="{{ $dt }}" {{ old('document_type') == $dt ? 'selected' : '' }}>{{ $dt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Número Documento</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('document_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Nombres</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Apellidos</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Empresa</label>
                    <input type="text" name="company" value="{{ old('company') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Tipo Visitante</label>
                    <select name="visitor_type" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="persona">Persona</option>
                        <option value="contratista">Contratista</option>
                        <option value="proveedor">Proveedor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Nacionalidad</label>
                    <input type="text" name="nationality" value="{{ old('nationality', 'Colombiana') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Fecha Nacimiento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-300">Notas</label>
                <textarea name="notes" rows="2" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('access.visitors.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-md font-semibold text-xs text-slate-300 uppercase tracking-widest hover:bg-slate-700">Cancelar</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Guardar</button>
            </div>
        </form>
    </div>
</x-access-layout>
