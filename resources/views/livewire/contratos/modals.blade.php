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
                                    <input wire:model="fechaIni" type="date" class="inpBase">
                                    @error('fechaIni')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Fecha Fin</label>
                                    <input wire:model="fechaFin" type="date" class="inpBase">
                                    @error('fechaFin')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Monto Renta</label>
                                    <input wire:model="montoRenta" type="text" class="inpBase">
                                    @error('montoRenta')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Deposito</label>
                                    <input wire:model="deposito" type="text" class="inpBase">
                                    @error('deposito')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Monto Contrato</label>
                                    <input wire:model="adicionales.montoContrato" type="text" class="inpBase">
                                    @error('adicionales.montoContrato')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="etiBase">Pena Entrega</label>
                                    <input wire:model="penaEntrega" type="text" class="inpBase"
                                    >
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

@if($verModalFirma)
<div class="modal-overlay">
    <div x-data="{
        dibujando: false,
        ctx: null,
        iniciar() {
            let canvas = $refs.canvasFirma;
            this.ctx = canvas.getContext('2d');
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = 250;
            this.limpiar();
        },
        obtenerPos(e) {
            let rect = $refs.canvasFirma.getBoundingClientRect();
            let clientX = e.touches ? e.touches[0].clientX : e.clientX;
            let clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return { x: clientX - rect.left, y: clientY - rect.top };
        },
        empezar(e) {
            this.dibujando = true;
            let pos = this.obtenerPos(e);
            this.ctx.beginPath();
            this.ctx.moveTo(pos.x, pos.y);
        },
        mover(e) {
            if (!this.dibujando) return;
            let pos = this.obtenerPos(e);
            this.ctx.lineTo(pos.x, pos.y);
            this.ctx.strokeStyle = '#000000';
            this.ctx.lineWidth = 2;
            this.ctx.stroke();
        },
        parar() {
            this.dibujando = false;
        },
        limpiar() {
            this.ctx.fillStyle = '#ffffff';
            this.ctx.fillRect(0, 0, $refs.canvasFirma.width, $refs.canvasFirma.height);
        },
        guardar() {
            let dataUrl = $refs.canvasFirma.toDataURL('image/png');
            $wire.guardarFirma(dataUrl);
        }
    }" x-init="dragModal($el); iniciar()" class="modal-dialog" wire:ignore.self>
        <div class="modal-content">
            <div class="card cardPrin">
                <div class="card-header" style="cursor: move;">
                    <span>Capturar Firma de Contrato</span>
                </div>
                <div class="card-body" style="padding: 10px; max-height: 400px; overflow-y: auto;">
                    <label class="etiBase">Firme dentro del recuadro:</label>
                    <div class="w-100 border rounded bg-white mt-1">
                        <canvas x-ref="canvasFirma" style="touch-action: none; display: block; cursor: crosshair;"
                            @mousedown="empezar($event)" @mousemove="mover($event)" @mouseup="parar()" @mouseleave="parar()"
                            @touchstart.prevent="empezar($event)" @touchmove.prevent="mover($event)" @touchend="parar()"></canvas>
                    </div>
                </div>
                <div class="card-footer mt-3 d-flex justify-content-end gap-2">
                    <button type="button" @click="limpiar()" class="bot botNegro botChico">Limpiar</button>
                    <button type="button" wire:click.prevent="cancel()" class="bot botRojo botChico">Cancelar</button>
                    <button type="button" @click="guardar()" class="bot botVerde botChico">Guardar Firma</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif