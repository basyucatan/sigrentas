@section('title', __('Árbol casas'))
<div class="container">
    <div class="cardSec">
    <div class="cardSec-header">
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
        <div class="d-flex gap-2">
            <button class="bot botAzul botChico" wire:click="$set('expandCasas', [])" title="Replegar todo">
                <i class="bi bi-arrows-collapse"></i>
            </button>
            <button class="bot botVerde botChico" wire:click="crearCasa" title="Nueva Casa">
                <i class="bi bi-file-earmark-plus"></i>
            </button>
        </div>
    </div>
        <div class="cardSec-body">
            @include('livewire.arbolcasas.modals')
            <div style="max-height: 60vh; overflow-y: auto;">
                <ul class="list-unstyled">
                    @foreach ($arbol as $casa)
                        @php
                            $expanded = $expandCasas[$casa['id']] ?? false;
                            $hijos = collect($casa['cuartos']);
                        @endphp
                        @include('livewire.arbolcasas.nodo', [
                            'nodo' => $casa,
                            'texto' => $casa['casa'],
                            'expanded' => $expanded,
                            'hijos' => $hijos,
                        ])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>