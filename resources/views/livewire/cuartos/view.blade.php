@section('title', __('Cuartos'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardSec">
                <div class="cardSec-header" style="cursor: move;">
                    <span>Cuartos</span>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="Nuevo Cuarto">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardSec-body">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $cuartos->links() }}
                    </div>
                    @include('livewire.cuartos.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Cuarto</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cuartos as $row)
                                    <tr>

                                        <td>{{ $row->cuarto }}</td>
                                        <td>{{ $row->estatus }}</td>
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
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
