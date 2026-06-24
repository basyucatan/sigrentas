@if($verModalPrioridad)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Prioridad' : 'Crear Prioridad' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif

<div class="col-md-6">
    <label class="etiBase">Prioridad</label>
    <input wire:model="prioridad" type="text" class="inpBase" onfocus="this.select()">
    @error('prioridad') <span class="error text-danger">{{ $message }}</span> @enderror
</div>
<div class="col-md-6">
    <label class="etiBase">Diastolerancia</label>
    <input wire:model="diasTolerancia" type="text" class="inpBase" onfocus="this.select()">
    @error('diasTolerancia') <span class="error text-danger">{{ $message }}</span> @enderror
</div>
<div class="col-md-6">
    <label class="etiBase">Colorhex</label>
    <input wire:model="colorHex" type="text" class="inpBase" onfocus="this.select()">
    @error('colorHex') <span class="error text-danger">{{ $message }}</span> @enderror
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