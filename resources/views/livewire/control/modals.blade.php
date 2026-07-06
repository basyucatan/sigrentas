@if ($verModalTicket)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Ticket' : 'Nuevo Ticket' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 70vh; overflow-y: auto;">
                        <div class="row g-1">
                            <div class="col-12 col-md-3">
                                <label class="etiBase">Casa</label>
                                <select wire:model.live="IdCasa" wire:change="elegirCasa" class="inpBase">
                                    <option value="">...</option>
                                    @foreach ($casas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('IdCasa') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
<div class="col-12 col-md-3">
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
                            <div class="col-12 col-md-3">
                                <label class="etiBase">Técnico Asignado</label>
                                <select wire:model="IdTecnico" class="inpBase">
                                    <option value="">Seleccione técnico</option>
                                    @foreach ($tecnicos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('IdTecnico') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            @if ($IdPrioridad == 2)
                                <div class="col-12 col-md-3">
                                    <label class="etiBase">Fecha Programada</label>
                                    <input type="datetime-local" wire:model="fechaPro" class="inpBase">
                                    @error('fechaPro') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            <div class="col-12">
                                <label class="etiBase d-block">Prioridad</label>
                                <div class="btn-group w-100 flex-wrap" role="group">
                                    @foreach ($prioridads as $key => $value)
                                        <input type="radio" class="btn-check" wire:model.live="IdPrioridad"
                                            value="{{ $key }}" id="prioridad{{ $key }}" autocomplete="off">
                                        <label class="btn btn-outline-secondary btn-sm" for="prioridad{{ $key }}">{{ $value }}</label>
                                    @endforeach
                                </div>
                                @error('IdPrioridad') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label class="etiBase d-block">Falla</label>
                                <div class="btn-group w-100 flex-wrap" role="group">
                                    @foreach ($fallas as $key => $value)
                                        <input type="radio" class="btn-check" wire:model="IdFalla"
                                            value="{{ $key }}" id="falla{{ $key }}"
                                            {{ ($IdPrioridad == 2 && $key != 6) ? 'disabled' : '' }}
                                            autocomplete="off">
                                        <label class="btn btn-outline-secondary btn-sm" for="falla{{ $key }}">{{ $value }}</label>
                                    @endforeach
                                </div>
                                @error('IdFalla') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="etiBase d-block">Tipo</label>
                                <div class="btn-group w-100" role="group">
                                    @foreach ($tipos as $key => $value)
                                        <input type="radio" class="btn-check" wire:model="tipo"
                                            value="{{ $key }}" id="tipo{{ $key }}"
                                            {{ ($IdPrioridad == 2 && $key != 'entrega') ? 'disabled' : '' }}
                                            autocomplete="off">
                                        <label class="btn btn-outline-secondary btn-sm" for="tipo{{ $key }}">{{ $value }}</label>
                                    @endforeach
                                </div>
                                @error('tipo') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="etiBase d-block">Estatus</label>
                                <div class="btn-group w-100" role="group">
                                    @foreach ($lEstatus as $key => $value)
                                        <input type="radio" class="btn-check" wire:model="estatus"
                                            value="{{ $key }}" id="estatus{{ $key }}"
                                            {{ ($IdPrioridad == 2 && $key != 'proceso') ? 'disabled' : '' }}
                                            autocomplete="off">
                                        <label class="btn btn-outline-secondary btn-sm" for="estatus{{ $key }}">{{ $value }}</label>
                                    @endforeach
                                </div>
                                @error('estatus') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label class="etiBase">Descripción</label>
                                <textarea wire:model="ticket" class="inpBase"></textarea>
                                @error('ticket') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancelar()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif