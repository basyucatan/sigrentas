@section('title', __('Control'))

<div class="cardPrin" style="height: 75vh; display: flex; flex-direction: column;">
    <div class="cardPrin-header d-flex justify-content-between align-items-center">
        <span>Control de Mtto.</span>
        <button wire:click="crearTicket" class="bot botVerde botChico">
            <i class="bi bi-file-earmark-plus"></i>
        </button>
    </div>

    <div class="cardPrin-body overflow-auto flex-grow-1" style="font-size: 1.2rem;">
        @include('livewire.control.modals')
        <div class="d-flex justify-content-end mb-2">
            {{ $tickets->links() }}
        </div>
        <div class="row g-2 mb-1">
            <div class="col-6 col-md-2">
                <select wire:model.live="IdCasaFiltro" class="form-select inpSolo">
                    <option value="">Casa</option>
                    @foreach ($casas as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select wire:model.live="estatusFiltro" class="form-select inpSolo">
                    <option value="">Estatus</option>
                    @foreach ($lEstatus as $key => $value)
                        <option value="{{ $key }}">{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select wire:model.live="prioridadFiltro" class="form-select inpSolo">
                    <option value="">Prioridad</option>
                    @foreach ($prioridads as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select wire:model.live="IdTecnicoFiltro" class="form-select inpSolo">
                    <option value="">Técnico</option>
                    @foreach ($tecnicos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <input wire:model.live.debounce.300ms="keyWord" type="text" class="form-control inpBase" placeholder="Buscar ticket...">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-3">
            @foreach ($tickets as $item)
                @php
                    $claseBorde = match ($item->estatus) {
                        'pendiente' => 'border-warning',
                        'proceso' => 'border-primary',
                        'terminado' => 'border-success',
                        'cancelado' => 'border-dark',
                        default => 'border-secondary',
                    };
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-start border-4 {{ $claseBorde }}">
                        <div class="card-body p-2">
<div class="d-flex justify-content-between align-items-center mb-1">
    <span class="fw-bold">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
    <span class="fw-bold badge bg-{{ $item->anieja['color'] }}">{{ $item->anieja['texto'] }}</span>
    @if($item->tipo === 'entrega' && $item->fechaPro)
        <span class="badge bg-{{ $item->estatusEntrega['color'] }}">
            {{ $item->estatusEntrega['vencido'] ? 'X' : 'Ok' }}
        </span>
    @endif
    <span class="text-muted small">{{ Util::formatFecha($item->fechaSol, 'DDMMM HH:mm') }}</span>
</div>
                            <div class="row g-2">
                                <div class="col-6 small">
                                    <i class="bi bi-house"></i>
                                    <span class="fw-bold text-uppercase">{{ $item->cuarto->casa->casa ?? 'N/A' }}-{{ $item->cuarto->cuarto ?? 'N/A' }}</span>
                                    <span class="text-muted d-block">Mantenimiento</span>
                                    <strong>{{ ucfirst($item->tipo) }}</strong>
                                </div>
                                <div class="col-6 small">
                                    <span class="badge" style="background-color: {{ $item->anieja['colorPrioridadFondo'] }}; color: {{ $item->anieja['colorPrioridadTexto'] }};">
                                        {{ $prioridads[$item->IdPrioridad] ?? 'N/A' }}
                                    </span>
                                    <span class="text-muted d-block">Tipo de Falla</span>
                                    <strong>{{ $fallas[$item->IdFalla] ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="p-2 my-2 bg-light rounded border">
                                <span class="small">{{ $item->ticket }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-bold text-primary">
                                    <i class="bi bi-person"></i>
                                    <strong>{{ $item->tecnico->tecnico ?? 'Sin asignar' }}</strong>
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="bot botNegro botChico">📷</button>
                                    <button class="bot botNegro botChico">📋</button>
                                    <button wire:click="edit({{ $item->id }})" class="bot botNaranja botChico">
                                        <i class="bi-pencil-square"></i>
                                    </button>
                                    <button wire:click="destroy({{ $item->id }})" class="bot botRojo botChico" onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()">
                                        <i class="bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>