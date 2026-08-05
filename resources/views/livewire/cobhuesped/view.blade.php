@section('title', __('Estado de Cuenta'))
<div class="container-fluid p-0">
    @php
        $proximo = $analitica['proximoRecibo'];
        $saldoProximo = $proximo ? $proximo->montoRenta - $proximo->pagos->sum('montoPago') : 0;
        $semaforo = $analitica['estadoSemaforo'];
        $dias = $analitica['diasDiferencia'];
    @endphp

    @if ($proximo)
        @if ($semaforo === 'al_dia')
            <div class="cardPrin mb-3 border-start border-4 border-success bg-white shadow-sm">
                <div class="cardPrin-body p-3">
                    <div class="row align-items-center g-2">
                        <div class="col-12 col-md-8">
                            <span class="badge bg-success-subtle text-success border border-success mb-2 px-2 py-1 fs-7">
                                <i class="bi bi-check-circle-fill me-1"></i> ¡Vas al día con tus pagos!
                            </span>
                            <h5 class="m-0 fw-bold text-dark">
                                {{ $proximo->adicionales['concepto'] ?? 'Renta Mensual' }}
                            </h5>
                            <div class="small text-muted mt-1">
                                Tu siguiente fecha límite es el <strong class="text-dark">{{ $proximo->fechaVence }}</strong>. Gracias por tu puntualidad.
                            </div>
                        </div>
                        <div class="col-12 col-md-4 text-md-end">
                            <span class="text-uppercase text-muted fs-7 fw-semibold d-block">Monto del Próximo Recibo</span>
                            <h3 class="m-0 fw-bolder text-dark me-2 d-inline-block d-md-block">
                                ${{ number_format($saldoProximo, 2) }}
                            </h3>
                            <button wire:click="abrirModalPago({{ $proximo->id }})" class="bot botAzul botChico mt-md-2">
                                Adelantar Pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($semaforo === 'por_vencer')
            <div class="cardPrin mb-3 border-start border-4 border-warning bg-white shadow-sm">
                <div class="cardPrin-body p-3">
                    <div class="row align-items-center g-2">
                        <div class="col-12 col-md-8">
                            <span class="badge bg-warning-subtle text-dark border border-warning mb-2 px-2 py-1 fs-7">
                                Recibo Próximo a Vencer
                            </span>
                            <h5 class="m-0 fw-bold text-dark">
                                {{ $proximo->adicionales['concepto'] ?? 'Renta Mensual' }}
                            </h5>
                            <div class="small text-muted mt-1">
                                Vence en <strong class="text-dark">{{ $dias }} días</strong> ({{ $proximo->fechaVence }}).
                            </div>
                        </div>
                        <div class="col-12 col-md-4 text-md-end">
                            <span class="text-uppercase text-muted fs-7 fw-semibold d-block">Monto a Pagar</span>
                            <h3 class="m-0 fw-bolder text-dark me-2 d-inline-block d-md-block">
                                ${{ number_format($saldoProximo, 2) }}
                            </h3>
                            <button wire:click="abrirModalPago({{ $proximo->id }})" class="bot botVerde mt-md-2">
                                Realizar Pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="cardPrin mb-3 border-start border-4 border-danger bg-white shadow-sm">
                <div class="cardPrin-body p-3">
                    <div class="row align-items-center g-2">
                        <div class="col-12 col-md-8">
                            <span class="badge bg-danger-subtle text-danger border border-danger mb-2 px-2 py-1 fs-7">
                                Pago Vencido
                            </span>
                            <h4 class="m-0 fw-bold text-danger">
                                {{ $proximo->adicionales['concepto'] ?? 'Renta Mensual' }}
                            </h4>
                            <div class="small text-muted mt-1">
                                Presenta un retraso de <strong class="text-danger">{{ $dias }} días</strong>. Venció el {{ $proximo->fechaVence }}.
                            </div>
                        </div>
                        <div class="col-12 col-md-4 text-md-end">
                            <span class="text-uppercase text-muted fs-7 fw-semibold d-block">Monto Requerido</span>
                            <h3 class="m-0 fw-bolder text-danger me-2 d-inline-block d-md-block">
                                ${{ number_format($saldoProximo, 2) }}
                            </h3>
                            <button wire:click="abrirModalPago({{ $proximo->id }})" class="bot botVerde mt-md-2">
                                Pagar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="cardPrin mb-3 border-start border-4 border-success bg-white p-3 text-center shadow-sm">
            <h5 class="m-0 text-success fw-bold">¡Estás completamente al día!</h5>
            <span class="small text-muted">No tienes ningún recibo pendiente por liquidar.</span>
        </div>
    @endif

    <div class="cardPrin">
        <div class="cardPrin-header d-flex justify-content-between align-items-center p-2">
            <span class="fw-bold">Mi Historial</span>
            <div class="btn-group btn-group-sm" role="group">
                <input type="radio" class="btn-check" name="filtroVista" id="fProx" value="proximo" wire:model.live="filtroVista" checked>
                <label class="btn btn-outline-light" for="fProx">
                    Pendientes ({{ $analitica['pendientes']->count() }})
                </label>
                <input type="radio" class="btn-check" name="filtroVista" id="fHist" value="historico" wire:model.live="filtroVista">
                <label class="btn btn-outline-light" for="fHist">
                    Pagados ({{ $analitica['pagados']->count() }})
                </label>
            </div>
        </div>
        <div class="cardPrin-body p-2" style="max-height: 55vh; overflow-y: auto;">
            @php
                $listaAMostrar = $filtroVista === 'proximo' ? $analitica['pendientes'] : $analitica['pagados'];
            @endphp

            @if ($listaAMostrar->count() > 0)
                @foreach ($listaAMostrar as $recibo)
                    @php
                        $pagado = $recibo->pagos->sum('montoPago');
                        $saldo = $recibo->montoRenta - $pagado;
                        $esPagado = $saldo <= 0;

                        $fVence = \Carbon\Carbon::parse($recibo->fechaVence)->startOfDay();
                        $diasRestantes = \Carbon\Carbon::today()->diffInDays($fVence, false);

                        if ($esPagado) {
                            $claseBorde = 'border-secondary';
                        } else {
                            if ($diasRestantes <= 0) {
                                $claseBorde = 'border-danger';
                            } elseif ($diasRestantes > 0 && $diasRestantes <= 30) {
                                $claseBorde = 'border-warning';
                            } else {
                                $claseBorde = 'border-success';
                            }
                        }
                    @endphp
                    <div class="cardPrin mb-2 border-start border-4 {{ $claseBorde }} bg-light rounded">
                        <div class="cardPrin-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-dark fs-6 d-inline-block">
                                        {{ $recibo->adicionales['concepto'] ?? 'Recibo' }}
                                    </span>
                                    <span class="badge ms-1 @if ($esPagado) bg-success @else bg-secondary @endif">
                                        {{ $esPagado ? 'PAGADO' : 'PENDIENTE' }}
                                    </span>
                                    <div class="small text-muted">Vencimiento: <strong>{{ $recibo->fechaVence }}</strong></div>
                                </div>
                                <div class="text-end">
                                    <h6 class="m-0 fw-bold text-dark">${{ number_format($recibo->montoRenta, 2) }}</h6>
                                    @if (!$esPagado)
                                        <button wire:click="abrirModalPago({{ $recibo->id }})" class="bot botAzul botChico mt-1">Pagar</button>
                                    @endif
                                </div>
                            </div>

                            @if ($recibo->pagos->count() > 0)
                                <div class="mt-2 pt-2 border-top">
                                    <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Comprobantes de Abono</span>
                                    <table class="table table-sm tabBase m-0 mt-1">
                                        <thead>
                                            <tr>
                                                <th>Fecha Pago</th>
                                                <th>Monto</th>
                                                <th class="text-end">Comprobante</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recibo->pagos as $pago)
                                                <tr>
                                                    <td>{{ $pago->fecha }}</td>
                                                    <td>${{ number_format($pago->montoPago, 2) }}</td>
                                                    <td class="text-end">
                                                        @if (isset($pago->adicionales['foto']))
                                                            <a href="{{ asset('storage/' . $pago->adicionales['foto']) }}"
                                                                target="_blank"
                                                                class="bot botAzul botChico p-0 px-1 me-1">Ver Foto</a>
                                                            <button wire:click="abrirModalFoto({{ $pago->id }})"
                                                                class="bot botGris botChico p-0 px-1"
                                                                title="Cambiar foto">✎</button>
                                                        @else
                                                            <button wire:click="abrirModalFoto({{ $pago->id }})"
                                                                class="bot botVerde botChico p-0 px-1">+ Foto</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4 text-muted">
                    {{ $filtroVista === 'proximo' ? 'No tienes mensualidades ni cobros pendientes.' : 'No hay historial de recibos pagados aún.' }}
                </div>
            @endif
        </div>
    </div>
    @include('livewire.cobhuesped.modals')
</div>