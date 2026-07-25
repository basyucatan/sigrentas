@section('title', __('Gastos'))
<div class="container-fluid p-0">
    <div class="row g-2">
        <div class="col-12 col-lg-4">
            <div class="cardPrin">
                <div class="cardPrin-header d-flex justify-content-between align-items-center">
                    <span>Administración de Gastos</span>
                    <button class="bot botVerde" wire:click="create" title="Nuevo Gasto">
                        <i class="bi bi-file-earmark-plus"></i> Nuevo
                    </button>
                </div>
                <div class="cardPrin-body p-2">
                    <div class="mb-3">
                        <label class="etiBase mb-1">Buscar Registros</label>
                        <div class="position-relative w-100">
                            <input wire:model.lazy="keyWord" class="inpBase w-100" wire:keydown.escape="$set('keyWord','')" onfocus="this.select()" placeholder="Buscar por monto, estatus...">
                            @if($keyWord)
                                <button type="button" wire:click="$set('keyWord','')" class="bot botNegro botChico position-absolute end-0 top-50 translate-middle-y border-0 me-1">
                                    X
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="etiBase mb-1">Modo de Vista / Filtro</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="modoVistaGroup" id="modoMisGastos" value="mis_gastos" wire:model.live="modoVista" autocomplete="off">
                            <label class="btn btn-outline-secondary btn-sm" for="modoMisGastos">Mis Gastos</label>
                            <input type="radio" class="btn-check" name="modoVistaGroup" id="modoAutorizaciones" value="por_autorizar" wire:model.live="modoVista" autocomplete="off">
                            <label class="btn btn-outline-secondary btn-sm" for="modoAutorizaciones">Por Autorizar</label>
                        </div>
                    </div>
                    <div class="bg-light rounded border p-2 mb-3">
                        <div class="fw-bold mb-2 etiBase">Consulta & IA (Rango de Fechas)</div>
                        <div class="row gx-1 gy-1">
                            <div class="col-6">
                                <label class="etiBase">Inicio</label>
                                <input type="date" wire:model="fechaInicio" class="inpSolo w-100">
                            </div>
                            <div class="col-6">
                                <label class="etiBase">Fin</label>
                                <input type="date" wire:model="fechaFin" class="inpSolo w-100">
                            </div>
                            <div class="col-12 mt-2">
                                <button type="button" class="bot botNaranja w-100" wire:click="calcularTotalIA">
                                    <i class="bi bi-cpu"></i> Sumar con IA
                                </button>
                            </div>
                        </div>
                        @if($totalCalculadoIA !== null)
                            <div class="mt-2 text-center p-2 bg-white rounded border">
                                <span class="d-block small text-muted">Total Sumado:</span>
                                <span class="fw-bold text-success h5 mb-0">${{ number_format($totalCalculadoIA, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="cardPrin">
                <div class="cardPrin-header d-flex justify-content-between align-items-center">
                    <span>{{ $modoVista == 'mis_gastos' ? 'Listado de Mis Gastos' : 'Gastos Pendientes de Autorización' }}</span>
                    <div>
                        {{ $this->filteredGastos->links() }}
                    </div>
                </div>
                <div class="cardPrin-body p-2">
                    <div class="d-block d-md-none">
                        <div class="row g-2">
                            @forelse($this->filteredGastos as $row)
                                @php
                                    $bordeColor = match($row->estatus) {
                                        'autorizado' => 'border-success',
                                        'efectuado' => 'border-info',
                                        'cancelado' => 'border-danger',
                                        default => 'border-warning'
                                    };
                                @endphp
                                <div class="col-12">
                                    <div class="cardPrin border-start border-4 {{ $bordeColor }}">
                                        <div class="cardPrin-header d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">${{ number_format($row->monto, 2) }}</span>
                                            <span class="badge {{ str_replace('border-', 'bg-', $bordeColor) }}">{{ strtoupper($row->estatus) }}</span>
                                        </div>
                                        <div class="cardPrin-body bg-light rounded border p-2 my-1">
                                            <div class="etiBase">Fecha: {{ $row->fecha }}</div>
                                            <div class="etiBase">Efectuó: {{ $row->Efectuo->name ?? $row->IdEfectuo }}</div>
                                            <div class="etiBase">Concepto: {{ $row->adicionales['concepto'] ?? 'Sin concepto' }}</div>
                                        </div>
                                        <div class="cardPrin-footer d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($row->foto)
                                                    <a href="{{ asset('storage/gastos/' . $row->foto) }}" target="_blank" class="bot botNegro botChico">
                                                        <i class="bi bi-image"></i> Ver Foto
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja botChico">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})" class="bot botRojo botChico" onclick="confirm('¿Eliminar registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-3">No hay gastos para mostrar.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="tablaCont d-none d-md-block">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Efectuó</th>
                                    <th>Estatus</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th>Foto</th>
                                    <th width="80">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->filteredGastos as $row)
                                    <tr>
                                        <td>{{ $row->Efectuo->name ?? $row->IdEfectuo }}</td>
                                        <td>
                                            @php
                                                $badgeClase = match($row->estatus) {
                                                    'autorizado' => 'bg-success',
                                                    'efectuado' => 'bg-info',
                                                    'cancelado' => 'bg-danger',
                                                    default => 'bg-warning text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClase }}">{{ strtoupper($row->estatus) }}</span>
                                        </td>
                                        <td class="fw-bold">${{ number_format($row->monto, 2) }}</td>
                                        <td>{{ $row->fecha }}</td>
<td class="text-center">
    <a href="{{ $row->foto ? asset('storage/gastos/' . $row->foto) : '#' }}"
        @if($row->foto) target="_blank" @endif
        class="bot botChico {{ $row->foto ? 'botVerde border border-success' : 'botRojo border border-danger disabled' }}"
        @if(!$row->foto) tabindex="-1" aria-disabled="true" onclick="return false;" @endif>
        <i class="{{ $row->foto ? 'fas fa-eye' : 'bi bi-image' }}"></i>
    </a>
</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja botChico" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})" class="bot botRojo botChico" onclick="confirm('¿Eliminar registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">No hay gastos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.gastos.modals')
</div>