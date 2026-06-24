@if ($verModalTicket)
    <div class="modal-overlay">
        <div x-data="{
            idCasa: @entangle('IdCasa'),
            idCuarto: @entangle('IdCuarto'),
            listaCuartos: {{ json_encode(\App\Models\Cuarto::all()) }},
            get cuartosFiltrados() {
                if (!this.idCasa) return [];
                return this.listaCuartos.filter(c => c.IdCasa == this.idCasa);
            }
        }" x-init="dragModal($el);
        $watch('idCasa', value => { idCuarto = ''; })" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="card">
                    <div class="card-header" style="cursor: move;">{{ $selected_id ? 'Editar Ticket' : 'Nuevo Ticket' }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="etiBase">Casa</label>
                                <select x-model="idCasa" class="inpBase">
                                    <option value="">Seleccione casa</option>
                                    @foreach ($casas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('IdCasa') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="etiBase">Cuarto</label>
                                <select x-model="idCuarto" class="inpBase">
                                    <option value="">Seleccione cuarto</option>
                                    <template x-for="c in cuartosFiltrados" :key="c.id">
                                        <option :value="c.id" x-text="c.cuarto"></option>
                                    </template>
                                </select>
                                @error('IdCuarto') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 mb-3">
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
                            <div class="col-12 mb-3">
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
                            <div class="col-md-6 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <label class="etiBase d-block">Estatus</label>
                                <div class="btn-group w-100" role="group">
                                    @foreach ($lEstatus as $key => $value)
                                        <input type="radio" class="btn-check" wire:model="estatus"
                                            value="{{ $key }}" id="estatus{{ $key }}" autocomplete="off">
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
                            <div class="col-md-6">
                                <label class="etiBase">Técnico Asignado</label>
                                <select wire:model="IdTecnico" class="inpBase">
                                    <option value="">Seleccione técnico</option>
                                    @foreach ($tecnicos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('IdTecnico') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button wire:click="cancelar" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click="save" class="bot botVerde botChico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif