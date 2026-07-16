@section('title', __('Asistencias'))
<div class="cardPrin">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="cardSec mb-3">
                <div class="cardSec-header">
                    Registro
                </div>
                <div class="cardSec-body text-center">
                    @if (session()->has('mensaje'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="justificacion" class="etiBase">Justificación / Observación</label>
                        <textarea id="justificacion" class="inpBase w-100" rows="3" placeholder="Escribe aquí el motivo en caso de retraso o incidencia..." wire:model="justificacion"></textarea>
                    </div>
                    <button id="btnChecada" type="button" class="bot botVerde px-4 py-2" onclick="obtenerUbicacionYRegistrar()" wire:loading.attr="disabled" wire:target="registrarAsistencia">
                        <span id="textoChecada" wire:loading.remove wire:target="registrarAsistencia">
                            <i class="bi bi-geo-alt-fill"></i> Registrar
                        </span>
                        <span id="cargandoChecada" style="display:none;">
                            ⏳ Obteniendo ubicación...
                        </span>
                        <span wire:loading wire:target="registrarAsistencia">
                            ⏳ Registrando...
                        </span>
                    </button>
                        <button class="bot botVerde botChico" wire:click="imprimirNomina" 
                            wire:loading.attr="disabled" wire:target="imprimirNomina" title="Imprimir nómina">
                            <span wire:loading.remove wire:target="imprimirNomina"><i class="bi bi-printer"></i></span>
                            <span wire:loading wire:target="imprimirNomina">⏳</span>
                        </button>
                </div>
            </div>
            <div class="cardSec">
                <div class="cardSec-header">
                    Mapa de Ubicaciones
                </div>
                <div class="cardSec-body p-0" wire:ignore>
                    <div id="IdMapa" data-casas="{{ json_encode($todasLasCasas) }}" style="height: 280px; width: 100%; border-radius: 0 0 8px 8px;"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="cardSec">
                <div class="cardSec-header d-flex justify-content-between align-items-center">
                    <span>Historial</span>
                </div>
                <div class="cardSec-body">
                    @include('livewire.asistencias.modals')
                    <div class="mb-3 d-flex justify-content-end">{{ $asistencias->links() }}</div>
                    @php
                        $semanas = $asistencias->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->fecha)->format('Y-W');
                        });
                    @endphp
                    @forelse($semanas as $llaveSemana => $registrosSemana)
                        @php
                            $primerDia = \Carbon\Carbon::parse($registrosSemana->first()->fecha)->startOfWeek();
                            $ultimoDia = \Carbon\Carbon::parse($registrosSemana->first()->fecha)->endOfWeek();
                            $resumenSueldo = $this->calcularSueldoSemana($registrosSemana);
                        @endphp
                        <div class="mb-4">
                            <div class="border-bottom mb-2 pb-2 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                <div>
                                    <strong>Semana: del {{ $primerDia->format('d/m/Y') }} al {{ $ultimoDia->format('d/m/Y') }}</strong>
                                </div>
                                <div class="d-flex flex-wrap gap-2" style="font-size: 0.85rem;">
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-2">
                                        Desc: -${{ number_format($resumenSueldo['descuentoTotal'], 2) }}
                                    </span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-2">
                                        Sueldo: ${{ number_format($resumenSueldo['sueldoNeto'], 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-none d-md-block table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th class="text-end">Acciones / Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registrosSemana as $row)
                                            @php
                                                $esHoy = \Carbon\Carbon::parse($row->fecha)->isToday();
                                                $adicionales = $row->adicionales;
                                            @endphp
                                            <tr class="{{ $esHoy ? 'table-warning' : '' }}">
                                                <td><strong>{{ \Carbon\Carbon::parse($row->fecha)->format('d/m/Y') }}</strong></td>
                                                <td>
                                                    <div><strong>{{ $row->horaEnt }}</strong></div>
                                                </td>
                                                <td>
                                                    <div><strong>{{ $row->horaSal !== '00:00:00' ? $row->horaSal : '---' }}</strong></div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                        <div>
                                                            @if(is_array($adicionales))
                                                                @if(isset($adicionales['penaEntradaId']))
                                                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2" style="font-size: 0.7rem;">Pena Ent.</span>
                                                                @endif
                                                                @if(isset($adicionales['penaSalidaId']))
                                                                    <span class="badge bg-warning bg-opacity-10 text-dark px-2 py-1 rounded-2 ms-1" style="font-size: 0.7rem;">Pena Sal.</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2" style="font-size: 0.7rem;">Cumplido</span>
                                                            @endif
                                                        </div>
                                                        <button wire:click="iniciarEdicion({{ $row->id }})" class="bot botNaranja botChico">
                                                            <i class="bi-pencil-square"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-block d-md-none">
                                @foreach($registrosSemana as $row)
                                    @php
                                        $esHoy = \Carbon\Carbon::parse($row->fecha)->isToday();
                                        $adicionales = $row->adicionales;
                                    @endphp
                                    <div class="card p-3 mb-2 {{ $esHoy ? 'bg-warning bg-opacity-10' : '' }}">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><strong>{{ \Carbon\Carbon::parse($row->fecha)->format('d/m/Y') }}</strong></span>
                                            <button wire:click="iniciarEdicion({{ $row->id }})" class="bot botNaranja botChico">
                                                <i class="bi-pencil-square"></i>
                                            </button>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <small class="text-muted">ENTRADA</small>
                                                <div><strong>{{ $row->horaEnt }}</strong></div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">SALIDA</small>
                                                <div><strong>{{ $row->horaSal !== '00:00:00' ? $row->horaSal : '---' }}</strong></div>
                                            </div>
                                        </div>
                                        <div class="border-top pt-2">
                                            @if(is_array($adicionales))
                                                @if(isset($adicionales['penaEntradaId']))
                                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-2" style="font-size: 0.7rem;">Pena Ent.</span>
                                                @endif
                                                @if(isset($adicionales['penaSalidaId']))
                                                    <span class="badge bg-warning bg-opacity-10 text-dark px-2 py-1 rounded-2 ms-1" style="font-size: 0.7rem;">Pena Sal.</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2" style="font-size: 0.7rem;">Cumplido</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            No se encontraron registros de asistencia.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <script>
        let mapa = null;
        let marcadorUsuario = null;
        let ubicacionActual = null;
        const escalaIcono = 0.5;
        function crearIcono(color) {
            return L.icon({
                iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${color}.png`,
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25 * escalaIcono, 41 * escalaIcono],
                iconAnchor: [12 * escalaIcono, 41 * escalaIcono],
                popupAnchor: [1 * escalaIcono, -34 * escalaIcono],
                shadowSize: [41 * escalaIcono, 41 * escalaIcono]
            });
        }
        const iconos = {
            blue: crearIcono('blue'),
            green: crearIcono('green'),
            orange: crearIcono('orange')
        };
        function actualizarMarcadorUsuario(lat, lng) {
            if (!mapa) return;
            mapa.invalidateSize();
            mapa.setView([lat, lng], 16);
            if (marcadorUsuario) {
                marcadorUsuario.setLatLng([lat, lng]);
            } else {
                marcadorUsuario = L.marker([lat, lng], {
                    icon: iconos.blue
                }).addTo(mapa);
            }
        }
        function inicializarMapa() {
            let contenedorMapa = document.getElementById('IdMapa');
            if (!contenedorMapa) return;
            if (mapa) {
                mapa.remove();
                mapa = null;
                marcadorUsuario = null;
            }
            let todasLasCasas = JSON.parse(contenedorMapa.getAttribute('data-casas') || '[]');
            mapa = L.map('IdMapa').setView([20.9673, -89.6237], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
            todasLasCasas.forEach(function(casa) {
                if (!casa.ubicacion) return;
                let partes = casa.ubicacion.split(',');
                if (partes.length !== 2) return;
                let latCasa = parseFloat(partes[0].trim());
                let lonCasa = parseFloat(partes[1].trim());
                let color = parseInt(casa.esAsignada) === 1 ? 'green' : 'orange';
                L.marker([latCasa, lonCasa], {
                    icon: iconos[color]
                }).addTo(mapa).bindPopup(casa.casa || 'Casa ID: ' + casa.id);
            });
            if (ubicacionActual) {
                actualizarMarcadorUsuario(ubicacionActual.lat, ubicacionActual.lng);
            }
        }
        function obtenerUbicacionYRegistrar() {
            if (!ubicacionActual) {
                alert('Aún se está obteniendo tu ubicación.');
                return;
            }
            actualizarMarcadorUsuario(
                ubicacionActual.lat,
                ubicacionActual.lng
            );
            @this.registrarAsistencia(
                {{ auth()->id() ?? 1 }},
                ubicacionActual.lat + ',' + ubicacionActual.lng
            );
        }
        document.addEventListener('livewire:navigated', function() {
            inicializarMapa();
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    function(position) {
                        ubicacionActual = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        actualizarMarcadorUsuario(
                            ubicacionActual.lat,
                            ubicacionActual.lng
                        );
                    },
                    function(error) {
                        console.error(error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 10000
                    }
                );
            } else {
                alert('Tu navegador no soporta geolocalización.');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Livewire !== 'undefined') {
                inicializarMapa();
            }
        });
        Livewire.hook('morph.updated', ({ el }) => {
            if (document.getElementById('IdMapa') && !mapa) {
                inicializarMapa();
            } else if (mapa) {
                mapa.invalidateSize();
            }
        });
    </script>
</div>