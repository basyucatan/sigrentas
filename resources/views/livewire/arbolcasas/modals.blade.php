@if ($verModalCasa)
    <div class="modal-overlay">
        <div class="modal-dialog" wire:ignore.self x-data x-init="dragModal($el)">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Casa' : 'Crear Casa' }}</span>
                    </div>
                    <div class="cardPrin-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="etiBase">Casa</label>
                                    <input wire:model="casa" type="text" class="inpBase">
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Dirección</label>
                                    <input wire:model="direccion" type="text" class="inpBase">
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">No. de Cuartos</label>
                                    <input wire:model="adicionales.noCuartos" type="number" class="inpBase">
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">URL Google Maps</label>
                                    <input wire:model="gmaps" type="text" class="inpBase">
                                </div>
                                <div class="col-12 mt-3" x-data>
                                    <label class="etiBase">Imagen de la Casa</label>
                                    <div class="border p-2 rounded bg-light"
                                        @paste.window="const items = $event.clipboardData.items; for (let i = 0; i < items.length; i++) { if (items[i].type.indexOf('image') !== -1) { const blob = items[i].getAsFile(); @this.upload('foto', blob); } }">
                                        <input wire:model="foto" type="file" class="inpBase" accept="image/*">
                                        <div class="mt-2 text-center">
                                            @if ($foto && !is_string($foto))
                                                <img src="{{ $foto->temporaryUrl() }}" style="max-height: 100px;">
                                                <button type="button" class="btn btn-sm text-danger d-block mx-auto"
                                                    wire:click="$set('foto', null)">Eliminar</button>
                                            @elseif(isset($adicionales['foto']))
                                                <img src="{{ asset('storage/' . $adicionales['foto']) }}"
                                                    style="max-height: 100px;">
                                            @endif
                                            @if (!$foto)
                                                <small class="text-muted d-block">Puedes pegar (Ctrl+V) una imagen
                                                    aquí</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click="guardarCasa()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($verModalCuarto)
    <div class="modal-overlay">
        <div class="modal-dialog" x-data x-init="dragModal($el)">
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Editar Cuarto #{{ $cuarto }}</span>
                    </div>
                    <div class="cardPrin-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="etiBase">Estatus</label>
                                    <select wire:model="estatus" class="inpBase">
                                        <option value="disponible">Disponible</option>
                                        <option value="ocupado">Ocupado</option>
                                        <option value="mantenimiento">Mantenimiento</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Número</label>
                                    <input wire:model="cuarto" type="text" class="inpBase" onfocus="this.select()">
                                    @error('cuarto')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click="guardarCuarto()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
