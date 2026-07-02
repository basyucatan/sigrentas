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
                                    <th>Idcuarto</th>
                                    <th>Idinquilino</th>
                                    <th>Idpropietario</th>
                                    <th>Fechaini</th>
                                    <th>Fechafin</th>
                                    <th>Montorenta</th>
                                    <th>Deposito</th>
                                    <th>Penaentrega</th>
                                    <th>Doccontrato</th>
                                    <th>Docinvmuebles</th>
                                    <th>Firma</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contratos as $row)
                                    <tr>
                                        <td>{{ $row->IdCuarto }}</td>
                                        <td>{{ $row->IdInquilino }}</td>
                                        <td>{{ $row->IdPropietario }}</td>
                                        <td>{{ $row->fechaIni }}</td>
                                        <td>{{ $row->fechaFin }}</td>
                                        <td>{{ $row->montoRenta }}</td>
                                        <td>{{ $row->deposito }}</td>
                                        <td>{{ $row->penaEntrega }}</td>
                                        <td>{{ $row->docContrato }}</td>
                                        <td>{{ $row->docInvMuebles }}</td>
                                        <td>
                                            @if($row->firma)
                                                <img src="{{ asset('storage/contratos/'.$row->firma) }}" width="50" class="img-thumbnail">
                                            @else
                                                <button type="button" 
                                                    wire:click="$dispatch('abrirFirmaModal', {modelId: {{ $row->id }}, modelo: 'Contrato', campo: 'firma', carpeta: 'contratos'})"
                                                    class="bot botAzul botChico">
                                                    Firmar
                                                </button>
                                            @endif
                                        </td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
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
                                    <tr><td colspan="12">No hay registros</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Componente de firma colocado al final --}}
            <livewire:firma />
        </div>
    </div>
</div>