@section('title', __('Contratos'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header" style="cursor: move;">
                    <span>Contratos</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" wire:keydown.escape="$set('keyWord','')"
                            onfocus="this.select()" placeholder="Buscar...">
                        @if ($keyWord)
                            <span wire:click="$set('keyWord','')" class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Contrato">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardPrin-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $contratos->links() }}
                    </div>
                    @include('livewire.contratos.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Casa|Cuarto</th>
                                    <th>Inquilino</th>
                                    <th>Propietario</th>
                                    <th>Fecha Ini</th>
                                    <th>Fecha Fin</th>
                                    <th>Monto</th>
                                    <th>Deposito</th>
                                    <th>Pena</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contratos as $row)
                                    <tr>
                                        <td>{{ $row->Cuarto->casa->casa }}
                                            <span class="badge bg-primary">{{ $row->Cuarto->cuarto }}</span>
                                        </td>
                                        <td>{{ $row->Inquilino->inquilino }}</td>
                                        <td>{{ $row->Propietario->propietario }}</td>
                                        <td>{{ $row->fechaIni }}</td>
                                        <td>{{ $row->fechaFin }}</td>
                                        <td>{{ $row->montoRenta }}</td>
                                        <td>{{ $row->deposito }}</td>
                                        <td>{{ $row->penaEntrega }}</td>
                                        <td width="120">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button class="bot botAzul botChico" wire:click="imprimir({{ $row->id }})" 
                                                    wire:loading.attr="disabled" wire:target="imprimir" title="Print Invoice">
                                                    <span wire:loading.remove wire:target="imprimir"><i class="bi bi-printer"></i></span>
                                                    <span wire:loading wire:target="imprimir">⏳</span>
                                                </button>
                                                <button wire:click="edit({{ $row->id }})"
                                                    class="bot botNaranja botChico" title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})"
                                                    class="bot botRojo botChico"
                                                    onclick="confirm('¿Estás seguro de eliminar este registro?') || event.stopImmediatePropagation()">
                                                    <i class="bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
