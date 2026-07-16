@section('title', __('Asignaciones'))
<div class="container-fluid p-0">
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Usuarios</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="buscadorUser" class="inpSolo" wire:keydown.escape="$set('buscadorUser','')"
                            onfocus="this.select()" placeholder="Buscar...">
                        @if ($buscadorUser)
                            <span wire:click="$set('buscadorUser','')" class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                </div>
                <div class="cardPrin-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($usuarios as $user)
                            <button type="button" wire:click="seleccionarUsuario({{ $user->id }})" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 {{ $IdUser == $user->id ? 'active' : '' }}">
                                <div class="text-truncate">
                                    <div class="fw-bold text-truncate" style="font-size: 14px;">{{ $user->name }}</div>
                                    <small class="{{ $IdUser == $user->id ? 'text-white-50' : 'text-muted' }} text-truncate d-block">{{ $user->email }}</small>
                                </div>
                            </button>
                        @empty
                            <div class="p-3 text-center text-muted small">No hay usuarios</div>
                        @endforelse
                    </div>
                    <div class="p-2 border-top">
                        {{ $usuarios->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            @if ($IdUser)
                @php $usuarioActivo = $usuarios->firstWhere('id', $IdUser); @endphp
                <div class="cardPrin mb-3">
                    <div class="cardPrin-header">
                        <span>Casas Asignadas a: {{ $usuarioActivo->name }}</span>
                    </div>
                    <div class="cardPrin-body">
                        <div class="row g-2">
                            @forelse($asignaciones as $asig)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="cardSec h-100 d-flex flex-column justify-content-between p-2">
                                        <div class="text-truncate">
                                            <div class="fw-bold text-truncate text-primary" style="font-size: 14px;">{{ $asig->Casa->casa }}</div>
                                            <small class="text-muted text-truncate d-block">{{ $asig->Casa->direccion }}</small>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2 pt-2 border-top">
                                            <button wire:click="desasignarCasa({{ $asig->id }})" class="bot botRojo botChico py-1" title="Quitar asignación">
                                                <i class="bi bi-trash"></i> Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center p-4 text-muted">
                                    Este usuario no tiene casas asignadas actualmente.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="cardPrin">
                    <div class="cardPrin-header">
                        <span>Casas</span>
                        <div class="me-2 position-relative" style="display:inline-block;">
                            <input wire:model.lazy="buscadorCasa" class="inpSolo" wire:keydown.escape="$set('buscadorCasa','')"
                                onfocus="this.select()" placeholder="Buscar...">
                            @if ($buscadorCasa)
                                <span wire:click="$set('buscadorCasa','')" class="bot botNegro botChico"
                                    style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                    X
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="cardPrin-body">
                        <div class="row g-2">
                            @forelse($casasDisponibles as $casa)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="cardSec h-100 d-flex flex-column justify-content-between p-2">
                                        <div class="text-truncate">
                                            <div class="fw-bold text-truncate" style="font-size: 14px;">{{ $casa->casa }}</div>
                                            <small class="text-muted text-truncate d-block">{{ $casa->direccion }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between gap-1 mt-2 pt-2 border-top">
                                            {{-- <button wire:click="asignarCasaATodos({{ $casa->id }})" class="bot botAzul botChico py-1 text-nowrap" title="Asignar a todos los usuarios">
                                                <i class="bi bi-people-fill"></i> A Todos
                                            </button> --}}
                                            <button wire:click="asignarCasa({{ $casa->id }})" class="bot botVerde botChico py-1 text-nowrap" title="Asignar a este usuario">
                                                <i class="bi bi-plus-circle"></i> Asignar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center p-4 text-muted">
                                    No hay más casas disponibles para asignar.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="cardPrin h-100 d-flex align-items-center justify-content-center p-5">
                    <div class="text-center text-muted">
                        <i class="bi bi-arrow-left-circle display-4 mb-2 d-block"></i>
                        <h5>Selecciona un usuario del panel izquierdo para gestionar sus casas</h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>