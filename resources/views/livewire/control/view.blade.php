@section('title', __('Control'))
<div class="cardPrin" style="height: 75vh;">
    <div class="cardPrin-header">
        <span>Control de Mtto.</span>
        <button wire:click="crearTicket" class="bot botVerde botChico">
            <i class="bi bi-file-earmark-plus"></i>
        </button>
    </div>
    <div class="cardPrin-body" style="flex: 1 1 auto; overflow-y: auto; padding: 1rem;">
        @include('livewire.control.modals')
        <div class="row mb-3">
            <div class="col-md-2">
                <select wire:model.live="IdCasaFiltro" class="inpBase">
                    <option value="">Casa</option>
                    @foreach ($casas as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model.live="estatusFiltro" class="inpBase">
                    <option value="">Estatus</option>
                    @foreach ($lEstatus as $value)
                        <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model.live="prioridadFiltro" class="inpBase">
                    <option value="">Prioridad</option>
                    @foreach ($prioridads as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model.live="IdTecnicoFiltro" class="inpBase">
                    <option value="">Técnico</option>
                    @foreach ($tecnicos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input wire:model.live.debounce.300ms="keyWord" type="text" class="inpBase"
                    placeholder="Buscar ticket...">
            </div>
        </div>

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
{{-- {{ dd($item->anieja) }} --}}
    <div class="card mb-2 shadow-sm border-start border-4 {{ $claseBorde }}">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span class="badge bg-{{ $item->anieja['color'] }}">{{ $item->anieja['texto'] }}</span>
                <span class="text-muted small">{{ Util::formatFecha($item->fechaSol, 'DDMMM HH:mm') }}</span>
            </div>

            <div class="row g-2">
                <div class="col-6 small">
                    <i class="bi bi-house"></i>
                    <strong>{{ $item->cuarto->casa->casa ?? 'N/A' }}-{{ $item->cuarto->cuarto ?? 'N/A' }}</strong>
                    <span class="text-muted d-block" style="font-size: 0.65rem;">Tipo</span>
                    {{ ucfirst($item->tipo) }}
                </div>

                <div class="col-6 small">
                    <span class="badge"
                        style="background-color: {{ $item->anieja['colorPrioridadFondo'] }};
                               color: {{ $item->anieja['colorPrioridadTexto'] }};">
                        {{ $prioridads[$item->IdPrioridad] ?? 'N/A' }}
                    </span>

                    <span class="text-muted d-block" style="font-size: 0.65rem;">Falla</span>
                    {{ $fallas[$item->IdFalla] ?? 'N/A' }}
                </div>
            </div>

            <div class="p-2 bg-light rounded border">
                <span class="small">{{ $item->ticket }}</span>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    <i class="bi bi-person"></i>
                    {{ $item->tecnico->tecnico ?? 'Sin asignar' }}
                </div>

                <div class="d-flex gap-1">
                    <button class="bot botNegro botChico">📷</button>

                    <button class="bot botNegro botChico">📋</button>

                    <button wire:click="edit({{ $item->id }})" class="bot botNaranja botChico">
                        <i class="bi-pencil-square"></i>
                    </button>

                    <button wire:click="destroy({{ $item->id }})"
                        class="bot botRojo botChico"
                        onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()">
                        <i class="bi-trash3-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
    </div>
</div>
