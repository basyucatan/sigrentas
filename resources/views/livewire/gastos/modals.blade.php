@if($verModalGasto)
    <div class="modal-overlay">
        <div x-data="{}" x-init="dragModal($el)" class="modal-dialog" wire:ignore.self>
            <div class="modal-content">
                <div class="cardPrin">
                    <div class="cardPrin-header" style="cursor: move;">
                        <span>{{ $selected_id ? 'Editar Gasto' : 'Crear Gasto' }}</span>
                    </div>
                    <div class="cardPrin-body" style="padding: 10px; max-height: 450px; overflow-y: auto;">
                        <form wire:submit.prevent="save">
                            <div class="row gx-2 gy-2">
                                @if ($selected_id)
                                    <input type="hidden" wire:model="selected_id">
                                @endif
                                <div class="col-md-12">
                                    <label class="etiBase">Comprobante / Foto</label>
                                    <input wire:model="archivoTemp" type="file" class="inpBase" accept="image/*" capture="environment">
                                    <div wire:loading wire:target="archivoTemp" class="text-primary small mt-1 fw-bold">
                                        <i class="bi bi-cpu spinner-border spinner-border-sm"></i> Analizando comprobante con IA...
                                    </div>
                                    @error('archivoTemp') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                @if(isset($adicionales['notas_detectadas']) && count($adicionales['notas_detectadas']) > 1)
                                    <div class="col-md-12">
                                        <div class="alert alert-info p-2 mb-0 small">
                                            <strong>IA detectó {{ count($adicionales['notas_detectadas']) }} notas en la foto:</strong>
                                            <ul class="mb-0 ps-3">
                                                @foreach($adicionales['notas_detectadas'] as $n)
                                                    <li>${{ number_format($n['monto'], 2) }} - {{ $n['fecha'] }} ({{ $n['concepto'] }})</li>
                                                @endforeach
                                            </ul>
                                            <span class="text-muted">Se asignó la primera nota al formulario actual.</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <label class="etiBase">Efectuó</label>
                                    <select wire:model="IdEfectuo" class="inpBase">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($listaUsuarios as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdEfectuo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Autorizó</label>
                                    <select wire:model="IdAutorizo" class="inpBase">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($listaUsuarios as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdAutorizo') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="etiBase">Estatus</label>
                                    <div class="btn-group w-100" role="group">
                                        @foreach($listaEstatus as $opcion)
                                            <input type="radio" class="btn-check" name="estatusGroup" id="est_{{ $opcion }}" value="{{ $opcion }}" wire:model="estatus" autocomplete="off">
                                            <label class="btn btn-outline-secondary btn-sm text-uppercase" for="est_{{ $opcion }}">{{ $opcion }}</label>
                                        @endforeach
                                    </div>
                                    @error('estatus') <span class="error text-danger d-block">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Monto</label>
                                    <input wire:model="monto" type="number" step="0.01" class="inpBase" onfocus="this.select()">
                                    @error('monto') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="etiBase">Fecha</label>
                                    <input wire:model="fecha" type="date" class="inpBase" onfocus="this.select()">
                                    @error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="etiBase">Concepto / Adicionales</label>
                                    <input wire:model="adicionales.concepto" type="text" class="inpBase" onfocus="this.select()" placeholder="Concepto extraído...">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardPrin-footer mt-3 d-flex justify-content-end gap-2">
                        <button wire:click.prevent="cancel()" class="bot botNegro botChico">Cerrar</button>
                        <button wire:click.prevent="save()" class="bot botVerde botChico" wire:loading.attr="disabled">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif