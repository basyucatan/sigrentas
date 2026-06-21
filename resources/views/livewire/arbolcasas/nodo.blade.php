<li class="mb-2">
    {{-- Nodo Nivel 1 (Casa) --}}
    <div class="d-flex align-items-center py-2 px-3 border rounded shadow-sm arbol1 nodoArbol arbol"
        onclick="manejadorClick(event, 
            () => @this.call('toggleCasa', {{ $nodo['id'] }}), 
            () => @this.call('editarCasa', {{ $nodo['id'] }})
        )">
        
        <div class="me-3">
            @if(!empty($nodo['adicionales']['foto']))
                <img src="{{ asset('storage/' . $nodo['adicionales']['foto']) }}?v={{ time() }}" 
                    class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light rounded-circle border" 
                     style="width: 40px; height: 40px; font-size: 1.2rem;">🏠</div>
            @endif
        </div>

        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
            <span class="fw-bold text-dark">{{ $texto }}
                <span class="badge bg-secondary ms-2" style="font-size: 0.65rem;">{{ count($hijos) }}</span>
            </span>
            <div class="d-flex gap-2">
                <button class="bot botVerde botChico" wire:click="generarCuartos({{ $nodo['id'] }})">⚙️</button>
                <button class="bot botRojo botChico" onclick="event.stopPropagation(); if(confirm('¿Eliminar?')) @this.call('eliminarCasa', {{ $nodo['id'] }})">X</button>
            </div>
        </div>
    </div>

    {{-- Nodo Nivel 2 (Cuartos) --}}
    @if ($expanded && count($hijos) > 0)
        <ul class="list-unstyled mt-1">
            @foreach ($hijos as $cuarto)
                <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center arbol2 nodoArbol arbol"
                    onclick="manejadorClick(event, () => {}, () => @this.call('editarCuarto', {{ $cuarto['id'] }}))">
                    
                    <span class="small fw-semibold text-secondary">
                        <span class="badge {{ $cuarto['estatus'] == 'disponible' ? 'bg-success' : ($cuarto['estatus'] == 'ocupado' ? 'bg-warning' : 'bg-secondary') }} me-2"
                            style="width: 80px;">{{ strtoupper($cuarto['estatus']) }}</span>
                        Cuarto #{{ $cuarto['cuarto'] }}
                    </span>
                    
                    <button class="bot botNaranja botChico" onclick="event.stopPropagation(); if(confirm('¿Eliminar?')) @this.call('eliminarCuarto', {{ $cuarto['id'] }})">X</button>
                </li>
            @endforeach
        </ul>
    @endif
</li>
@once
    <script>
        let timerClick = null;
        function manejadorClick(evento, accionSimple, accionDoble) {
            if (evento.target.closest('button')) return;
            if (timerClick == null) {
                timerClick = setTimeout(() => {
                    accionSimple();
                    timerClick = null;
                }, 250);
            } else {
                clearTimeout(timerClick);
                timerClick = null;
                accionDoble();
            }
        }
    </script>
@endonce