@if ($verModalContrato)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Contrato' : 'Crear Contrato' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form gy-2>
                            <div class="row">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-6">
                                    <label class="etiBase">Firma</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input wire:model="firma" type="text" class="inpBase" readonly>
                                        <button type="button" 
                                            wire:click="$dispatch('abrirFirmaModal', {modelId: {{ $selected_id ?? 0 }}, modelo: 'Contrato', campo: 'firma', carpeta: 'contratos'})" 
                                            class="bot botAzul botChico">Firmar</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Idcuarto</label>
                                    <input wire:model="IdCuarto" type="text" class="inpBase" onfocus="this.select()">
                                    @error('IdCuarto')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Idinquilino</label>
                                    <input wire:model="IdInquilino" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('IdInquilino')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Idpropietario</label>
                                    <input wire:model="IdPropietario" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('IdPropietario')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Fechaini</label>
                                    <input wire:model="fechaIni" type="text" class="inpBase" onfocus="this.select()">
                                    @error('fechaIni')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Fechafin</label>
                                    <input wire:model="fechaFin" type="text" class="inpBase" onfocus="this.select()">
                                    @error('fechaFin')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Montorenta</label>
                                    <input wire:model="montoRenta" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('montoRenta')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Deposito</label>
                                    <input wire:model="deposito" type="text" class="inpBase" onfocus="this.select()">
                                    @error('deposito')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Penaentrega</label>
                                    <input wire:model="penaEntrega" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('penaEntrega')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Doccontrato</label>
                                    <input wire:model="docContrato" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('docContrato')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Docinvmuebles</label>
                                    <input wire:model="docInvMuebles" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('docInvMuebles')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Firma</label>
                                    <input wire:model="firma" type="text" class="inpBase" onfocus="this.select()">
                                    @error('firma')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
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
