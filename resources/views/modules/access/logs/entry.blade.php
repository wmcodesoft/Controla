<x-access-layout>
    <div class="rounded-xl bg-gradient-to-r from-slate-800 to-indigo-900 p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-300">Ingreso/Salida</p>
            <h2 class="text-xl font-bold text-white">Registrar Ingreso</h2>
        </div>
        <a href="{{ route('access.logs.exit.page') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-300 hover:text-white">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Salida rápida
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <div x-data="entryFlow()">
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Tipo de Ingreso</label>
                    <div class="flex gap-3">
                        <label class="relative flex-1 cursor-pointer">
                            <input type="radio" x-model="vehicleMode" value="pedestrian" class="sr-only peer">
                            <div class="p-3 border-2 rounded-xl text-center transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-900/30 border-slate-700 hover:border-slate-600 bg-slate-800">
                                <svg class="w-6 h-6 mx-auto text-slate-500 peer-checked:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="mt-1 text-sm font-medium text-slate-300 peer-checked:text-emerald-300">Peatonal</p>
                            </div>
                        </label>
                        <label class="relative flex-1 cursor-pointer">
                            <input type="radio" x-model="vehicleMode" value="vehicle" class="sr-only peer">
                            <div class="p-3 border-2 rounded-xl text-center transition-all peer-checked:border-cyan-500 peer-checked:bg-cyan-900/30 border-slate-700 hover:border-slate-600 bg-slate-800">
                                <svg class="w-6 h-6 mx-auto text-slate-500 peer-checked:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0V21h14v-5m0 0h3l2-4-2-4h-3l-2 4h-1"/></svg>
                                <p class="mt-1 text-sm font-medium text-slate-300 peer-checked:text-cyan-300">Vehicular</p>
                            </div>
                        </label>
                    </div>

                    <hr class="my-6 border-slate-800">

                    <form method="POST" action="{{ route('access.logs.entry.store') }}" x-ref="mainForm" @submit="submitting = true">
                        @csrf
                        <input type="hidden" name="access_type" x-model="computedAccessType">
                        <input type="hidden" name="person_type" x-model="personType">
                        <input type="hidden" name="person_id" x-model="personId">

                        {{-- ── BLOQUE 1: DOCUMENTO ── --}}
                        <div class="bg-slate-800 rounded-xl p-4 mb-6">
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Documento de identidad</label>

                            <div class="mb-3 p-3 bg-slate-950/50 border border-slate-700/50 rounded-lg">
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Escáner QR Cédula</label>
                                @include('modules.access.partials.qr-scan-field')
                            </div>

                            <div x-show="scanError" class="mb-3 p-3 bg-red-900/40 border border-red-700 rounded-lg">
                                <p class="text-sm text-red-200 font-medium" x-text="scanError"></p>
                            </div>

                            @error('document_number')
                                <div class="mb-3 p-3 bg-red-900/40 border border-red-700 rounded-lg">
                                    <p class="text-sm text-red-200 font-medium">{{ $message }}</p>
                                </div>
                            @enderror

                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="documentNumber" @input.debounce.500ms="lookupDocument()" placeholder="Ingrese o escanee el documento..." class="block w-full pl-10 rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" :class="errors.document_number && !personId ? 'border-red-500' : ''">
                            </div>

                            <div x-show="looking" class="mt-3 text-center py-2">
                                <svg class="animate-spin h-5 w-5 text-indigo-400 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </div>

                            {{-- Persona encontrada --}}
                            <div x-show="personFound && !looking" class="mt-3">
                                <div class="p-3 rounded-xl border flex items-center justify-between"
                                     :class="personType === 'resident' ? 'bg-teal-900/30 border-teal-800' : 'bg-emerald-900/30 border-emerald-800'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold"
                                             :class="personType === 'resident' ? 'bg-gradient-to-br from-teal-500 to-teal-700' : 'bg-gradient-to-br from-emerald-500 to-emerald-700'">
                                            <span x-text="personType === 'resident' ? 'RE' : 'VI'"></span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium text-white" x-text="personName"></p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider"
                                                      :class="personType === 'resident' ? 'bg-teal-800 text-teal-200' : 'bg-emerald-800 text-emerald-200'"
                                                      x-text="personType === 'resident' ? 'Persona' : 'Visitante'"></span>
                                            </div>
                                            <p class="text-xs text-slate-400" x-text="personDoc"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="clearPerson()" class="text-xs text-red-400 hover:text-red-300 font-medium">Cambiar</button>
                                </div>
                                <div x-show="personType === 'resident' && personStructure" class="mt-2 text-xs text-slate-500">
                                    Unidad: <span class="text-teal-300" x-text="personStructure"></span>
                                </div>
                            </div>

                            {{-- Documento desconocido: solicitar datos --}}
                            <div x-show="status === 'unknown' && !looking" x-transition class="mt-4 p-4 bg-slate-950/60 border border-amber-800/50 rounded-xl space-y-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    <p class="text-sm font-semibold text-amber-300">Persona no registrada</p>
                                </div>
                                <p class="text-xs text-slate-400">Ingrese los datos para registrar el ingreso.</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Nombre *</label>
                                        <input type="text" x-model="newVisitorName" placeholder="Nombre completo" class="block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-white focus:border-amber-500 focus:ring-amber-500" :class="errors.newVisitor && !newVisitorName ? 'border-red-500' : ''">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Teléfono</label>
                                        <input type="text" x-model="newVisitorPhone" placeholder="Opcional" class="block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-white focus:border-amber-500 focus:ring-amber-500">
                                    </div>
                                </div>
                                <button type="button" @click="createAndSelectVisitor()" :disabled="!newVisitorName" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-500 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg text-xs font-semibold text-white transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Registrar y seleccionar
                                </button>
                            </div>
                        </div>

                        @if (session('error'))
                            <div class="mb-4 p-3 bg-red-900/40 border border-red-700 rounded-lg text-sm text-red-200">{{ session('error') }}</div>
                        @endif

                        {{-- ── BLOQUE 2: VEHÍCULO (solo visitante + vehicular) ── --}}
                        <div x-show="vehicleMode === 'vehicle' && personType === 'visitor' && personId" x-transition class="bg-cyan-900/30 rounded-xl p-4 mb-6 border border-cyan-800">
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Datos del Vehículo</label>
                            <p class="text-xs text-slate-500 mb-3">Ingrese la placa y color del vehículo del visitante.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Placa *</label>
                                    <input type="text" x-model="vehiclePlate" @input.debounce.500ms="lookupVehicle()" placeholder="ABC 123" class="block w-full rounded-lg bg-slate-950 border-slate-700 text-white uppercase focus:border-cyan-500 focus:ring-cyan-500" :class="errors.vehicle && !vehicleId ? 'border-red-500' : ''">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Color</label>
                                    <input type="text" x-model="vehicleColor" placeholder="Rojo, Negro..." class="block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-cyan-500 focus:ring-cyan-500">
                                </div>
                            </div>
                            <input type="hidden" name="vehicle_id" x-model="vehicleId">
                            <div x-show="vehicleId" class="mt-2 text-xs text-emerald-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Vehículo encontrado y seleccionado
                            </div>
                        </div>

                        {{-- ── BLOQUE 3: CAMPOS DEL FORMULARIO ── --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300">Anfitrión *</label>
                                <select name="host_id" x-ref="hostSelect" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500 @error('host_id') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    @foreach($hosts as $host)
                                    <option value="{{ $host->id }}">{{ $host->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('host_id')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300">Ubicación *</label>
                                <select name="location_id" required class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500 @error('location_id') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300">Unidad Destino</label>
                                <select name="structure_id" x-ref="structureSelect" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">No aplica</option>
                                    @foreach($structures as $s)
                                    <option value="{{ $s->id }}">{{ $s->full_path }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300">Propósito</label>
                                <input type="text" name="purpose" x-model="purpose" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Reunión, entrega, etc.">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300">Empresa a Visitar</label>
                                <input type="text" name="company_visited" x-model="companyVisited" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opcional">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-300">Notas</label>
                            <textarea name="notes" rows="2" class="mt-1 block w-full rounded-lg bg-slate-950 border-slate-700 text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Observaciones adicionales..."></textarea>
                        </div>

                        {{-- ── BOTONES ── --}}
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-800">
                            <a href="{{ route('access.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg font-semibold text-xs text-slate-300 hover:bg-slate-700 transition-colors">Cancelar</a>
                            <button type="submit" :disabled="!personId" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    :class="personId ? 'bg-emerald-600 hover:bg-emerald-500' : 'bg-slate-700'">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Registrar Ingreso
                            </button>
                        </div>
                    </form>

                    {{-- Overlay de carga --}}
                    <div x-show="submitting" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                        <div class="bg-slate-900 rounded-2xl border border-slate-700 p-8 text-center shadow-2xl">
                            <svg class="animate-spin h-10 w-10 text-emerald-400 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <p class="mt-4 text-sm font-semibold text-white">Registrando ingreso...</p>
                            <p class="mt-1 text-xs text-slate-400">Por favor espere</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function entryFlow() {
            return {
                scanBuffer: '',
                scanError: '',
                vehicleMode: 'pedestrian',
                documentNumber: '',
                looking: false,
                status: null,
                personFound: false,
                personType: null,
                personId: null,
                personName: '',
                personDoc: '',
                personStructure: null,
                personStructureId: null,
                vehiclePlate: '',
                vehicleColor: '',
                vehicleId: null,
                purpose: '',
                companyVisited: '',
                newVisitorName: '',
                newVisitorPhone: '',
                submitting: false,
                errors: {},

                get computedAccessType() {
                    if (!this.personType) return this.vehicleMode === 'vehicle' ? 'visitor_vehicle' : 'visitor';
                    const isVehicle = this.vehicleMode === 'vehicle';
                    if (this.personType === 'resident') {
                        return isVehicle ? 'resident_vehicle' : 'resident';
                    }
                    return isVehicle ? 'visitor_vehicle' : 'visitor';
                },

                async lookupDocument() {
                    if (this.documentNumber.length < 3) {
                        this.clearPerson();
                        return;
                    }
                    this.looking = true;
                    this.scanError = '';
                    this.status = null;
                    this.errors = {};
                    try {
                        const res = await fetch('{{ route("access.logs.lookup-document") }}?document_number=' + encodeURIComponent(this.documentNumber));
                        const data = await res.json();
                        if (data.found) {
                            this.personFound = true;
                            this.personType = data.type;
                            this.personId = data.person.id;
                            this.personName = data.person.full_name;
                            this.personDoc = data.person.document_type + ' ' + data.person.document_number;
                            this.personStructure = data.person.structure_name || null;
                            this.personStructureId = data.person.structure_id || null;
                            this.status = 'found';

                            this.$nextTick(() => {
                                if (this.personType === 'resident') {
                                    if (this.personStructureId && this.$refs.structureSelect) {
                                        this.$refs.structureSelect.value = this.personStructureId;
                                    }
                                    if (this.$refs.hostSelect) {
                                        this.$refs.hostSelect.value = this.personId;
                                    }
                                } else if (this.personType === 'visitor' && data.last_visit) {
                                    this.purpose = data.last_visit.purpose || '';
                                    this.companyVisited = data.last_visit.company_visited || '';
                                    if (data.last_visit.host_id && this.$refs.hostSelect) {
                                        this.$refs.hostSelect.value = data.last_visit.host_id;
                                    }
                                    if (data.last_visit.structure_id && this.$refs.structureSelect) {
                                        this.$refs.structureSelect.value = data.last_visit.structure_id;
                                    }
                                    if (data.last_visit.vehicle_id) {
                                        this.vehicleId = data.last_visit.vehicle_id;
                                    }
                                } else if (this.$refs.structureSelect) {
                                    this.$refs.structureSelect.value = '';
                                }
                            });
                        } else if (data.status === 'unknown') {
                            this.status = 'unknown';
                            this.personFound = false;
                            this.personType = null;
                            this.personId = null;
                        } else {
                            this.clearPerson();
                        }
                    } catch (e) {
                        this.clearPerson();
                    }
                    this.looking = false;
                },

                async createAndSelectVisitor() {
                    if (!this.newVisitorName) return;
                    this.errors = {};
                    try {
                        const res = await fetch('{{ route("access.logs.create-visitor") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({
                                document_number: this.documentNumber,
                                first_name: this.newVisitorName,
                                last_name: '',
                                phone: this.newVisitorPhone,
                            })
                        });
                        const data = await res.json();
                        if (data.ok) {
                            this.personFound = true;
                            this.personType = 'visitor';
                            this.personId = data.person.id;
                            this.personName = data.person.full_name;
                            this.personDoc = data.person.document_type + ' ' + data.person.document_number;
                            this.status = 'found';
                            this.newVisitorName = '';
                            this.newVisitorPhone = '';
                        }
                    } catch (e) {
                        this.scanError = 'Error al registrar. Intente de nuevo.';
                    }
                },

                clearPerson() {
                    this.personFound = false;
                    this.personType = null;
                    this.personId = null;
                    this.personName = '';
                    this.personDoc = '';
                    this.personStructure = null;
                    this.personStructureId = null;
                    this.status = null;
                    this.vehiclePlate = '';
                    this.vehicleColor = '';
                    this.vehicleId = null;
                    this.purpose = '';
                    this.companyVisited = '';
                    this.newVisitorName = '';
                    this.newVisitorPhone = '';
                    this.errors = {};
                    this.$nextTick(() => {
                        if (this.$refs.hostSelect) this.$refs.hostSelect.value = '';
                        if (this.$refs.structureSelect) this.$refs.structureSelect.value = '';
                    });
                },

                async lookupVehicle() {
                    if (this.vehiclePlate.length < 3 || this.personType !== 'visitor') return;
                    try {
                        const res = await fetch('{{ route("access.vehicles.visitor-vehicle") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ plate: this.vehiclePlate, color: this.vehicleColor, visitor_id: this.personId })
                        });
                        const data = await res.json();
                        if (data.ok) {
                            this.vehicleId = data.vehicle.id;
                        }
                    } catch (e) {
                        this.vehicleId = null;
                    }
                },

                async handleScan() {
                    let parts = this.scanBuffer.trim().split(/[|\t]/);
                    if (parts.length < 5) return;
                    let numero = parts[0];
                    this.scanBuffer = '';
                    this.documentNumber = numero;
                    await this.lookupDocument();
                }
            }
        }
    </script>
    @endpush
</x-access-layout>
