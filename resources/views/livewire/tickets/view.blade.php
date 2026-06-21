@section('title', __('Tickets'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="cursor: move;">
                    <span>Tickets</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" 
                        wire:keydown.escape="$set('keyWord','')"
                        onfocus="this.select()" placeholder="Buscar...">
                        @if($keyWord)
                            <span wire:click="$set('keyWord','')" 
                                class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Ticket">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>                   
                    </div>                
                </div>
                <div class="card-body">    
                    <div class="d-flex justify-content-end mb-2">
                        {{ $tickets->links() }}
                    </div>                               
                    @include('livewire.tickets.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
								<th>Idcuarto</th>
								<th>Idfalla</th>
								<th>Idautor</th>
								<th>Idtecnico</th>
								<th>Tipo</th>
								<th>Estatus</th>
								<th>Ticket</th>
								<th>Fechasol</th>
								<th>Fechafin</th>
<th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $row)
                                    <tr>
                                        
								<td>{{ $row->IdCuarto }}</td>
								<td>{{ $row->IdFalla }}</td>
								<td>{{ $row->IdAutor }}</td>
								<td>{{ $row->IdTecnico }}</td>
								<td>{{ $row->tipo }}</td>
								<td>{{ $row->estatus }}</td>
								<td>{{ $row->ticket }}</td>
								<td>{{ $row->fechaSol }}</td>
								<td>{{ $row->fechaFin }}</td>

                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})"
                                                        class="bot botNaranja botChico"
                                                        title="Editar">
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
