@if($verModalCuarto)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="card">
                    <div class="card-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Cuarto' : 'Crear Cuarto' }}</span>
                    </div>
                    <div class="card-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

                                <div class="col-md-6">
                                    <label class="etiBase">Idcasa</label>
                                    <input wire:model="IdCasa" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('IdCasa') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Cuarto</label>
                                    <input wire:model="cuarto" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('cuarto') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>                            
                                <div class="col-md-6">
                                    <label class="etiBase">Estatus</label>
                                    <input wire:model="estatus" type="text" class="inpBase"  onfocus="this.select()">
                                    @error('estatus') <span class="error text-danger">{{ $message }}</span> @enderror
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