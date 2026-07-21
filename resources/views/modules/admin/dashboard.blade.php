@php
    use App\Enums\ArchiveReason;
    use App\Enums\CompanyAlertBucket;
    use App\Support\Tenancy\CompanySubscriptionState;

    $fmt = fn (float $n) => '$'.number_format($n, 0, ',', '.');

    $dashQuery = fn (array $merge = []) => route('admin.dashboard', array_merge(
        request()->only(['alert', 'archive', 'company', 'view']),
        $merge
    ));

    $bucketStyles = [
        'current' => 'border-emerald-800/60 bg-emerald-950/30 text-emerald-200',
        'due_soon' => 'border-amber-800/60 bg-amber-950/30 text-amber-200',
        'overdue' => 'border-red-800/60 bg-red-950/30 text-red-200',
        'archived' => 'border-slate-700 bg-slate-900/80 text-slate-300',
    ];
@endphp

<x-admin-layout title="Resumen plataforma">
    <div class="flex flex-col flex-1 min-h-0 gap-3" x-data="{ expanded: {} }">
        {{-- Alertas --}}
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            @foreach (CompanyAlertBucket::cases() as $bucket)
                @php
                    $active = ($alert ?? null) === $bucket->value || ($alert === null && $bucket === CompanyAlertBucket::Current && false);
                    $isActive = ($alert ?? null) === $bucket->value;
                    $count = $alertCounts[$bucket->value] ?? 0;
                @endphp
                <a href="{{ $dashQuery(['alert' => $bucket->value, 'archive' => null]) }}"
                   class="inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-medium transition {{ $isActive ? $bucketStyles[$bucket->value].' ring-1 ring-violet-500/40' : 'border-slate-800 bg-slate-900/60 text-slate-400 hover:border-slate-700' }}">
                    {{ $bucket->label() }}
                    <span class="tabular-nums font-semibold">{{ $count }}</span>
                </a>
            @endforeach
            @if ($alert)
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-500 hover:text-slate-300 ml-1">Quitar filtro</a>
            @endif
        </div>

        @if (($alert ?? null) === 'archived')
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ $dashQuery(['alert' => 'archived', 'archive' => null]) }}"
                   class="text-xs px-2.5 py-1 rounded-md {{ ! $archiveType ? 'bg-violet-600 text-white' : 'bg-slate-800 text-slate-400' }}">
                    Todos archivados
                </a>
                <a href="{{ $dashQuery(['alert' => 'archived', 'archive' => 'recovery']) }}"
                   class="text-xs px-2.5 py-1 rounded-md {{ ($archiveType ?? null) === 'recovery' ? 'bg-violet-600 text-white' : 'bg-slate-800 text-slate-400' }}">
                    Por recuperar
                </a>
                <a href="{{ $dashQuery(['alert' => 'archived', 'archive' => 'cancelled']) }}"
                   class="text-xs px-2.5 py-1 rounded-md {{ ($archiveType ?? null) === 'cancelled' ? 'bg-violet-600 text-white' : 'bg-slate-800 text-slate-400' }}">
                    Baja voluntaria
                </a>
            </div>
        @endif

        {{-- Cuerpo: mapa + detalle --}}
        <div class="flex flex-1 min-h-0 gap-3">
            {{-- Mapa árbol --}}
            <aside class="w-72 shrink-0 flex flex-col min-h-0 rounded-lg border border-slate-800 bg-slate-900/60">
                <div class="px-3 py-2 border-b border-slate-800 flex items-center justify-between gap-2 shrink-0">
                    <p class="text-xs font-semibold text-slate-300">Cartera</p>
                    <div class="flex gap-1">
                        <a href="{{ $dashQuery(['view' => null, 'company' => $selectedCompanyId]) }}"
                           class="text-[10px] px-2 py-0.5 rounded {{ ! $globalView ? 'bg-violet-600 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                            Árbol
                        </a>
                        <a href="{{ $dashQuery(['view' => 'global', 'company' => null]) }}"
                           class="text-[10px] px-2 py-0.5 rounded {{ $globalView ? 'bg-violet-600 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                            Global
                        </a>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto p-2 space-y-1">
                    @forelse ($companies as $company)
                        @php
                            $bucket = CompanySubscriptionState::bucket($company);
                            $isSelected = ! $globalView && (int) $selectedCompanyId === (int) $company->id;
                            $companyKey = 'c'.$company->id;
                        @endphp
                        <div class="rounded-md {{ $isSelected ? 'bg-violet-950/40 border border-violet-800/50' : 'border border-transparent' }}">
                            <div class="flex items-center gap-1 px-2 py-1.5">
                                <button type="button"
                                        class="text-slate-500 hover:text-slate-300 text-xs w-4 shrink-0"
                                        @click="expanded['{{ $companyKey }}'] = !expanded['{{ $companyKey }}']"
                                        x-text="expanded['{{ $companyKey }}'] !== false ? '▾' : '▸'">▾</button>
                                <a href="{{ $dashQuery(['company' => $company->id, 'view' => null]) }}"
                                   class="flex-1 min-w-0 text-left">
                                    <p class="text-sm font-medium text-slate-200 truncate">{{ $company->trade_name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">
                                        {{ $company->operational_clients_count }}/{{ $company->max_clients }} · {{ $bucket->label() }}
                                    </p>
                                </a>
                            </div>
                            <div x-show="expanded['{{ $companyKey }}'] !== false" x-cloak class="pb-1 pl-6 pr-2 space-y-0.5">
                                @foreach ($company->clients as $client)
                                    <p class="text-xs text-slate-500 truncate" title="{{ $client->name }}">
                                        {{ $client->name }}
                                        @if ($client->lifecycle?->value !== 'active')
                                            <span class="text-slate-600">· {{ $client->lifecycle?->label() }}</span>
                                        @endif
                                    </p>
                                @endforeach
                                @if ($company->clients->isEmpty())
                                    <p class="text-[10px] text-slate-600">Sin conjuntos</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-6">Sin empresas en este filtro.</p>
                    @endforelse
                </div>
            </aside>

            {{-- Detalle --}}
            <section class="flex-1 min-w-0 flex flex-col min-h-0 rounded-lg border border-slate-800 bg-slate-900/80">
                <div class="px-4 py-2 border-b border-slate-800 flex items-center justify-between gap-2 shrink-0">
                    <h3 class="text-sm font-semibold text-white">
                        @if ($globalView)
                            Vista global
                        @elseif ($selectedCompany)
                            {{ $selectedCompany->trade_name }}
                        @else
                            Detalle
                        @endif
                    </h3>
                    @if ($selectedCompany && ! $globalView)
                        <a href="{{ route('admin.companies.show', $selectedCompany) }}" class="text-xs text-violet-400 hover:text-violet-300">
                            Gestionar paquete
                        </a>
                    @endif
                </div>
                <div class="flex-1 overflow-y-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500 sticky top-0">
                            <tr>
                                @if ($globalView)
                                    <th class="px-4 py-2 text-left font-medium">Empresa</th>
                                @endif
                                <th class="px-4 py-2 text-left font-medium">Conjunto</th>
                                <th class="px-4 py-2 text-left font-medium hidden md:table-cell">Dirección</th>
                                <th class="px-4 py-2 text-left font-medium">Estado</th>
                                <th class="px-4 py-2 text-left font-medium">Vence</th>
                                <th class="px-4 py-2 text-right font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($detailRows as $row)
                                @php
                                    /** @var \App\Models\SecurityCompany $rowCompany */
                                    $rowCompany = $row['company'];
                                    $client = $row['client'];
                                    $bucket = CompanySubscriptionState::bucket($rowCompany);
                                    $days = CompanySubscriptionState::daysUntilRenewal($rowCompany);
                                @endphp
                                <tr class="hover:bg-slate-800/30">
                                    @if ($globalView)
                                        <td class="px-4 py-2.5">
                                            <a href="{{ $dashQuery(['company' => $rowCompany->id, 'view' => null]) }}"
                                               class="text-slate-200 hover:text-violet-300 font-medium">
                                                {{ $rowCompany->trade_name }}
                                            </a>
                                        </td>
                                    @endif
                                    <td class="px-4 py-2.5">
                                        @if ($client)
                                            <p class="font-medium text-slate-200">{{ $client->name }}</p>
                                            <p class="text-xs text-slate-600">{{ $client->slug }}</p>
                                        @else
                                            <span class="text-slate-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 hidden md:table-cell text-slate-400 max-w-xs truncate">
                                        {{ $client?->address ?: '—' }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        @if ($client)
                                            <span class="text-xs text-slate-400">{{ $client->lifecycle?->label() }}</span>
                                        @else
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs
                                                @if($bucket->value === 'current') bg-emerald-900/30 text-emerald-300
                                                @elseif($bucket->value === 'due_soon') bg-amber-900/30 text-amber-300
                                                @elseif($bucket->value === 'overdue') bg-red-900/30 text-red-300
                                                @else bg-slate-800 text-slate-400 @endif">
                                                {{ $bucket->label() }}
                                                @if ($rowCompany->archive_reason)
                                                    · {{ $rowCompany->archive_reason->label() }}
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-slate-400 tabular-nums">
                                        @if ($rowCompany->package_ends_at)
                                            {{ $rowCompany->package_ends_at->format('d M Y') }}
                                            @if ($days !== null && $days >= 0 && ! $rowCompany->archived_at)
                                                <span class="text-slate-600">({{ $days }}d)</span>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap space-x-2">
                                        @if ($client && $client->lifecycle?->value === 'active')
                                            @can('platform.companies.manage')
                                                <form action="{{ route('admin.companies.clients.release', [$rowCompany, $client]) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('¿Retirar conjunto y liberar cupo? Los datos quedan en retención.')">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-amber-400 hover:text-amber-300">Retirar</button>
                                                </form>
                                            @endcan
                                        @endif
                                        @if (! $client && ! $rowCompany->archived_at)
                                            @can('platform.companies.manage')
                                                <form action="{{ route('admin.companies.archive', $rowCompany) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('¿Archivar empresa y suspender servicio?')">
                                                    @csrf
                                                    <input type="hidden" name="archive_reason" value="cancelled">
                                                    <button type="submit" class="text-xs text-slate-400 hover:text-slate-300">Baja</button>
                                                </form>
                                            @endcan
                                        @endif
                                        <a href="{{ route('admin.companies.show', $rowCompany) }}" class="text-xs text-violet-400 hover:text-violet-300">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $globalView ? 6 : 5 }}" class="px-4 py-12 text-center text-sm text-slate-500">
                                        No hay registros para este filtro.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Anclas comerciales --}}
        <div class="shrink-0 rounded-lg border border-slate-800 bg-slate-900/60 px-4 py-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
            <span>Unitario manual: <strong class="text-slate-300 tabular-nums">{{ $fmt((float) $pricing->unit_price_manual) }}</strong>/mes</span>
            <span>Unitario hardware: <strong class="text-slate-300 tabular-nums">{{ $fmt((float) $pricing->unit_price_hardware) }}</strong>/mes</span>
            <span class="text-slate-600">·</span>
            <span>{{ $allCompanies->count() }} empresas · {{ $allCompanies->sum('operational_clients_count') }} conjuntos operativos</span>
            <a href="{{ route('admin.pricing.edit') }}" class="text-violet-400 hover:text-violet-300 ml-auto">Editar precios</a>
        </div>
    </div>
</x-admin-layout>
