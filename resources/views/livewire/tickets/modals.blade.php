@if($verModalTicket)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="card">
                    <div class="card-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Ticket' : 'Crear Ticket' }}</span>
                    </div>
                    <div class="card-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Idcuarto</label>
                                    <input wire:model="IdCuarto" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdCuarto') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idfalla</label>
                                    <input wire:model="IdFalla" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdFalla') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idautor</label>
                                    <input wire:model="IdAutor" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdAutor') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Idtecnico</label>
                                    <input wire:model="IdTecnico" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdTecnico') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Tipo</label>
                                    <input wire:model="tipo" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('tipo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Estatus</label>
                                    <input wire:model="estatus" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('estatus') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Ticket</label>
                                    <input wire:model="ticket" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('ticket') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Fechasol</label>
                                    <input wire:model="fechaSol" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('fechaSol') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Fechafin</label>
                                    <input wire:model="fechaFin" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('fechaFin') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            

                            </div>
                        </form>
                    </div>
                    <div class="card-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif