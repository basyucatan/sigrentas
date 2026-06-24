@if($verModalInquilino)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Inquilino' : 'Crear Inquilino' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

<div class="col-md-6">
    <label class="etiBase">Iduser</label>
    <input wire:model="IdUser" type="text" class="inpBase" onfocus="this.select()">
    @error('IdUser') <span class="error text-danger">{{ $message }}</span> @enderror
</div>
<div class="col-md-6">
    <label class="etiBase">Inquilino</label>
    <input wire:model="inquilino" type="text" class="inpBase" onfocus="this.select()">
    @error('inquilino') <span class="error text-danger">{{ $message }}</span> @enderror
</div>
<div class="col-md-6">
    <label class="etiBase">Telefono</label>
    <input wire:model="telefono" type="text" class="inpBase" onfocus="this.select()">
    @error('telefono') <span class="error text-danger">{{ $message }}</span> @enderror
</div>
<div class="col-md-6">
    <label class="etiBase">Activo</label>
    <input wire:model="activo" type="text" class="inpBase" onfocus="this.select()">
    @error('activo') <span class="error text-danger">{{ $message }}</span> @enderror
</div>

                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif