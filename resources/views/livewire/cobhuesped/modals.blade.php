@if($verModalPago)
    <div class="modal-overlay" 
        x-data="{ subiendoFoto: false }" 
        @paste.window="
            if (!@js($idPagoEdicion) || @js($idPagoEdicion)) {
                let items = ($event.clipboardData || $event.originalEvent.clipboardData).items;
                for (let index in items) {
                    let item = items[index];
                    if (item.kind === 'file' && item.type.includes('image')) {
                        let blob = item.getAsFile();
                        let file = new File([blob], 'comprobante_pasted.png', { type: item.type });
                        subiendoFoto = true;
                        @this.upload('foto', file, 
                            () => { subiendoFoto = false; }, 
                            () => { subiendoFoto = false; }
                        );
                    }
                }
            }
        "> 
        <div x-init="dragModal($el)" class="modal-dialog" style="max-width: 600px; width: 90%;">
            <div class="modal-content">
                <div class="cardPrin" style="overflow-y: auto; max-height: 90vh;">
                    <div class="cardPrin-header" style="cursor: move;">
                        {{ $idPagoEdicion ? 'Actualizar Comprobante de Pago' : 'Registrar Nuevo Pago' }}
                    </div>
                    <div class="cardPrin-body">
                        @if($idPagoEdicion)
                            <div class="alert alert-info p-2 mb-3" style="font-size: 12px;">
                                <i class="bi bi-info-circle me-1"></i>
                                El monto y la fecha del pago no pueden modificarse. Si cometiste un error, comunícate con administración.
                            </div>
                        @endif

                        <div style="margin-bottom: 15px;">
                            <label class="etiBase">Monto a Pagar</label>
                            <input wire:model="montoPago" type="number" step="0.01" class="inpBase" onfocus="this.select()" @if($idPagoEdicion) disabled @endif>
                            @error('montoPago') <span class="text-danger" style="font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label class="etiBase">Fecha de Pago</label>
                            <input wire:model="fechaPago" type="date" class="inpBase" @if($idPagoEdicion) disabled @endif>
                            @error('fechaPago') <span class="text-danger" style="font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label class="etiBase">Comprobante (Archivo, Cámara o Ctrl+V)</label>
                            <input type="file" wire:model="foto" accept="image/*" capture="environment" class="inpBase" wire:loading.attr="disabled" wire:target="foto" :disabled="subiendoFoto">
                            @error('foto') <span class="text-danger d-block" style="font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div wire:loading wire:target="foto" x-show="true">
                            <div style="text-align: center; border: 1px dashed #0d6efd; border-radius: 4px; padding: 15px; background-color: #f8f9fa; margin-top: 10px; margin-bottom: 10px;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status" style="margin-right: 5px;"></div>
                                <span class="etiBase" style="color: #0d6efd; font-weight: bold;">Procesando y subiendo imagen...</span>
                            </div>
                        </div>

                        <div x-show="subiendoFoto && !$wire.get('foto')" x-cloak style="text-align: center; border: 1px dashed #0d6efd; border-radius: 4px; padding: 15px; background-color: #f8f9fa; margin-top: 10px; margin-bottom: 10px;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status" style="margin-right: 5px;"></div>
                            <span class="etiBase" style="color: #0d6efd; font-weight: bold;">Procesando captura del portapapeles...</span>
                        </div>

                        <div wire:loading.remove wire:target="foto" x-show="!subiendoFoto">
                            @if ($foto)
                                <div style="text-align: center; border: 1px solid #ccc; border-radius: 4px; padding: 5px; background-color: #fff; margin-top: 10px;">
                                    <label class="etiBase" style="display: block; text-align: left; margin-bottom: 5px;">Vista previa nueva:</label>
                                    <img src="{{ $foto->temporaryUrl() }}" style="max-height: 200px; object-fit: contain; max-width: 100%;">
                                </div>
                            @elseif ($fotoActual)
                                <div style="text-align: center; border: 1px solid #ccc; border-radius: 4px; padding: 5px; background-color: #fff; margin-top: 10px;">
                                    <label class="etiBase" style="display: block; text-align: left; margin-bottom: 5px;">Comprobante actual:</label>
                                    <img src="{{ asset('storage/' . $fotoActual) }}" style="max-height: 200px; object-fit: contain; max-width: 100%;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="cardPrin-footer">
                        <button wire:click="$set('verModalPago', false)" class="bot botNegro" wire:loading.attr="disabled" wire:target="foto" :disabled="subiendoFoto">Cancelar</button>
                        <button wire:click="guardarPago" wire:loading.attr="disabled" :disabled="subiendoFoto" class="bot botVerde">
                            <span wire:loading.remove wire:target="guardarPago">
                                {{ $idPagoEdicion ? 'Actualizar Comprobante' : 'Guardar Pago' }}
                            </span>
                            <span wire:loading wire:target="guardarPago">
                                Guardando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif