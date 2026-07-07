@section('title', __('Contratos'))
<div class="cardPrin" style="height: 75vh; display: flex; flex-direction: column;">
    <div class="cardPrin-header d-flex justify-content-between align-items-center" style="cursor: move;">
        <span>Contratos</span>
        <div class="d-flex align-items-center gap-2">
            <div class="position-relative" style="display:inline-block;">
                <input wire:model.lazy="keyWord" class="inpSolo" wire:keydown.escape="$set('keyWord','')" onfocus="this.select()" placeholder="Buscar...">
                @if ($keyWord)
                    <span wire:click="$set('keyWord','')" class="bot botNegro botChico" style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">X</span>
                @endif
            </div>
            <button class="bot botVerde botChico" wire:click="create" title="Nuevo Contrato">
                <i class="bi bi-file-earmark-plus"></i>
            </button>
        </div>
    </div>
    <div class="cardPrin-body overflow-auto flex-grow-1" style="font-size: 1.2rem;">
        @include('livewire.contratos.modals')
        <div class="d-flex justify-content-end mb-2">
            {{ $contratos->links() }}
        </div>
        <div class="row g-3">
            @forelse($contratos as $row)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-start border-4 border-primary">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">#{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-muted small"><i class="bi bi-calendar-event"></i> {{ $row->fechaIni }} al {{ $row->fechaFin }}</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 small">
                                    <i class="bi bi-house"></i>
                                    <span class="fw-bold text-uppercase">{{ $row->Cuarto->casa->casa }}</span>
                                    <span class="badge bg-primary ms-1">{{ $row->Cuarto->cuarto }}</span>
                                    <span class="text-muted d-block mt-1">Inquilino</span>
                                    <strong>{{ $row->Inquilino->inquilino }}</strong>
                                </div>
                                <div class="col-6 small text-end">
                                    <span class="text-muted d-block">Propietario</span>
                                    <strong>{{ $row->Propietario->propietario }}</strong>
                                </div>
                            </div>
                            <div class="p-2 my-2 bg-light rounded border d-flex justify-content-around text-center small">
                                <div>
                                    <span class="text-muted d-block font-monospace" style="font-size: 0.75rem;">RENTA</span>
                                    <strong class="text-success">{{ Util::Dinero($row->montoRenta) }}</strong>
                                </div>
                                <div class="border-start mx-1"></div>
                                <div>
                                    <span class="text-muted d-block font-monospace" style="font-size: 0.75rem;">DEPÓSITO</span>
                                    <strong class="text-secondary">{{ Util::Dinero($row->deposito) }}</strong>
                                </div>
                                <div class="border-start mx-1"></div>
                                <div>
                                    <span class="text-muted d-block font-monospace" style="font-size: 0.75rem;">PENA</span>
                                    <strong class="text-danger">{{ Util::Dinero($row->penaEntrega) }}</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center gap-1">
                                <button class="bot botAzul botChico" wire:click="imprimir({{ $row->id }})" wire:loading.attr="disabled" wire:target="imprimir" title="Print Invoice">
                                    <span wire:loading.remove wire:target="imprimir"><i class="bi bi-printer"></i></span>
                                    <span wire:loading wire:target="imprimir">⏳</span>
                                </button>
                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja botChico" title="Editar">
                                    <i class="bi-pencil-square"></i>
                                </button>
                                <button wire:click="destroy({{ $row->id }})" class="bot botRojo botChico" onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                    <i class="bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>