@if ($verModalContrato)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Contrato' : 'Crear Contrato' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                        <form>
                            <div class="row gx-1 gy-1">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Casa</label>
                                    <select wire:model.live="IdCasa" wire:change="elegirCasa" class="inpBase">
                                        <option value="">...</option>
                                        @foreach ($casas as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdCasa')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Cuarto</label>
                                    <select wire:model="IdCuarto" class="inpBase">
                                        <option value="">...</option>
                                        @foreach ($this->cuartos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdCuarto')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Inquilino</label>
                                    <select wire:model="IdInquilino" class="inpBase">
                                        <option value="">...</option>
                                        @foreach ($this->inquilinos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdInquilino')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Propietario</label>
                                    <select wire:model="IdPropietario" class="inpBase">
                                        <option value="">...</option>
                                        @foreach ($this->propietarios as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdPropietario')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Fecha Ini</label>
                                    <input wire:model="fechaIni" type="date" class="inpBase" onfocus="this.select()">
                                    @error('fechaIni')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Fecha Fin</label>
                                    <input wire:model="fechaFin" type="date" class="inpBase" onfocus="this.select()">
                                    @error('fechaFin')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Monto Renta</label>
                                    <input wire:model="montoRenta" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('montoRenta')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Deposito</label>
                                    <input wire:model="deposito" type="text" class="inpBase" onfocus="this.select()">
                                    @error('deposito')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Pena Entrega</label>
                                    <input wire:model="penaEntrega" type="text" class="inpBase"
                                        onfocus="this.select()">
                                    @error('penaEntrega')
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
