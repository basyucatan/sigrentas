@section('title', __('Ocupación'))
<div class="container-fluid p-0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="row g-2">
        <div class="col-12 col-md-3">
            <div class="card cardPrin shadow-sm border-0 h-100">
                <div class="card-header bg-transparent fw-bold etiBase border-0 pb-0">
                    <i class="bi bi-sliders me-1"></i> Panel de Control
                </div>
                <div class="card-body d-flex flex-column gap-3">
                    <div>
                        <label class="etiBase fw-bold mb-1">Fecha de Corte</label>
                        <input type="date" wire:model.lazy="fechaCorte" class="form-control inpBase">
                    </div>

                    <div>
                        <label class="etiBase fw-bold mb-1">Modo de Visualización</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="vistaMode" id="vTabla" value="tabla" wire:model.live="vista">
                            <label class="btn btn-outline-secondary btn-sm" for="vTabla">
                                <i class="bi bi-table"></i> Tabla
                            </label>

                            <input type="radio" class="btn-check" name="vistaMode" id="vMapa" value="mapa" wire:model.live="vista">
                            <label class="btn btn-outline-secondary btn-sm" for="vMapa">
                                <i class="bi bi-geo-alt"></i> Mapa
                            </label>
                        </div>
                    </div>

                    <hr class="my-1">

                    <button wire:click="generarPdf" class="btn botPrin w-100">
                        <i class="bi bi-file-earmark-pdf"></i> Descargar Reporte PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9">
            <div class="cardPrin-body" style="max-height: 70vh;">
                @if($vista === 'tabla')
                    @forelse($casas as $casa)
                        <div class="card cardPrin shadow-sm border-0 mb-3">
                            <div class="card-header bg-light border-0 py-2">
                                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-house-door"></i> {{ strtoupper($casa->casa) }}</h6>
                            </div>
                            <div class="card-body p-0" style="max-height: 70vh;">
                                <div class="table-responsive">
                                    <table class="table tabBase table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Estatus</th>
                                                <th>Inquilino</th>
                                                <th class="text-end">Renta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($casa->cuartos as $cuarto)
                                                @php $contrato = $cuarto->contratos->first(); @endphp
                                                <tr>
                                                    <td class="fw-bold">{{ $cuarto->cuarto }}</td>
                                                    <td>
                                                        @if($contrato)
                                                            <span class="badge bg-danger">Ocupado</span>
                                                        @else
                                                            <span class="badge bg-success">Disponible</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($contrato)
                                                            <span class="fw-bold">{{ $contrato->inquilino?->inquilino ?? 'N/A' }}</span>
                                                            <div class="small text-muted">
                                                                Del: {{ \Carbon\Carbon::parse($contrato->fechaIni)->format('d/m/Y') }} 
                                                                {{ $contrato->fechaFin ? 'al ' . \Carbon\Carbon::parse($contrato->fechaFin)->format('d/m/Y') : '(Indefinido)' }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end fw-bold">
                                                        {{ $contrato ? '$' . number_format($contrato->montoRenta, 2) : '---' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info border-0 shadow-sm">No hay casas ni cuartos registrados en el sistema.</div>
                    @endforelse
                @endif
                @if($vista === 'mapa')
                    <div class="card cardPrin shadow-sm border-0">
                        <div class="card-body p-2">
                            <div x-data="{
                                mapa: null,
                                initMapa() {
                                    let el = $el;
                                    let rawData = el.getAttribute('data-casas');
                                    if (!rawData) return;

                                    let casas = JSON.parse(rawData);
                                    
                                    if (this.mapa) {
                                        this.mapa.remove();
                                        this.mapa = null;
                                    }

                                    this.mapa = L.map(el).setView([20.9673, -89.6237], 11);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '© OpenStreetMap'
                                    }).addTo(this.mapa);

                                    let escala = 0.5;
                                    let crearIcono = (color) => L.icon({
                                        iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${color}.png`,
                                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                        iconSize: [25 * escala, 41 * escala],
                                        iconAnchor: [12 * escala, 41 * escala],
                                        popupAnchor: [1 * escala, -34 * escala],
                                        shadowSize: [41 * escala, 41 * escala]
                                    });

                                    let iconos = {
                                        green: crearIcono('green'),
                                        orange: crearIcono('orange'),
                                        red: crearIcono('red')
                                    };

                                    let bounds = [];

                                    casas.forEach(casa => {
                                        if (!casa.lat || !casa.lng || (casa.lat === 0 && casa.lng === 0)) return;

                                        bounds.push([casa.lat, casa.lng]);

                                        let color = 'green';
                                        if (casa.ocupados > 0 && casa.disponibles > 0) color = 'orange';
                                        else if (casa.disponibles === 0 && casa.totalCuartos > 0) color = 'red';

                                        let html = `<div style='font-family: sans-serif; min-width: 200px;'>
                                            <strong style='font-size: 10pt; color: #0d6efd;'>${casa.casa}</strong><br>
                                            <small style='color: #6c757d;'>Cuartos: ${casa.totalCuartos} | Ocupados: ${casa.ocupados} | Libres: ${casa.disponibles}</small>
                                            <hr style='margin: 5px 0;'>
                                            <table style='width: 100%; font-size: 8pt; border-collapse: collapse;'>
                                                <thead>
                                                    <tr style='background: #f8f9fa;'>
                                                        <th style='text-align: left; padding: 2px;'>Cuarto</th>
                                                        <th style='text-align: left; padding: 2px;'>Estatus</th>
                                                        <th style='text-align: left; padding: 2px;'>Inquilino</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;

                                        casa.cuartos.forEach(c => {
                                            let colorEstatus = c.estatus === 'Ocupado' ? '#dc3545' : '#198754';
                                            html += `<tr>
                                                <td style='padding: 2px;'><b>${c.cuarto}</b></td>
                                                <td style='color: ${colorEstatus}; font-weight: bold; padding: 2px;'>${c.estatus}</td>
                                                <td style='padding: 2px;'>${c.inquilino}</td>
                                            </tr>`;
                                        });

                                        html += `</tbody></table></div>`;

                                        L.marker([casa.lat, casa.lng], { icon: iconos[color] })
                                            .addTo(this.mapa)
                                            .bindPopup(html);
                                    });


                                    setTimeout(() => {
                                        if (this.mapa) this.mapa.invalidateSize();
                                    }, 250);
                                }
                            }" x-init="initMapa()" data-casas="{{ json_encode($casasMapa) }}" style="height: 65vh; width: 100%;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>