@if($verModalAsistencia)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>            
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>Modificar Asistencia / Penalizaciones</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 15px; max-height: 450px; overflow-y: auto;">
                        <form gy-2>
                            @if ($selectedId)
                                <input type="hidden" wire:model="selectedId">
                            @endif
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="etiBase">Pena Entrada</label>
                                    <select wire:model="penaEntradaId" class="inpBase">
                                        <option value="">Sin Penalización</option>
                                        <option value="1">Un día completo (ID: 1)</option>
                                        <option value="2">Medio día (ID: 2)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Pena Salida</label>
                                    <select wire:model="penaSalidaId" class="inpBase">
                                        <option value="">Sin Penalización</option>
                                        <option value="1">Un día completo (ID: 1)</option>
                                        <option value="2">Medio día (ID: 2)</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="etiBase">Justificación Entrada</label>
                                    <input wire:model="justificacionEntrada" type="text" class="inpBase" onfocus="this.select()">
                                </div>
                                <div class="col-12">
                                    <label class="etiBase">Justificación Salida</label>
                                    <input wire:model="justificacionSalida" type="text" class="inpBase" onfocus="this.select()">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancelarEdicion()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="guardarEdicion()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif