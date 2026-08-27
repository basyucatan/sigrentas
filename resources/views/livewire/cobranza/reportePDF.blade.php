@php
    $filaNum = 0;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
</head>
<body>
    <header class="cabecera">
        <img src="{{ public_path('img/logo.png') }}" class="logo">
        <div style="margin-left: 50px;">
            <strong style="font-size: 12pt;">REPORTE DE COBRANZA POR CASA</strong><br>
            Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        </div>
    </header>

    <div style="margin-bottom: 20px;">
        <div style="background: #f8f9fa; padding: 8px 10px; border: 1px solid #dee2e6; margin-bottom: 12px;">
            <strong style="font-size: 10pt; text-transform: uppercase; color: #333;">PERIODO: DEL {{ $fechaIni }} AL {{ $fechaFin }}</strong>
        </div>

        @forelse($pagosPorCasa as $nombreCasa => $grupoPagos)
            <div style="page-break-inside: avoid; margin-bottom: 15px;">
                <div style="background: #e9ecef; padding: 6px 10px; border: 1px solid #ced4da; font-weight: bold; font-size: 10pt; color: #212529;">
                    CASA: {{ strtoupper($nombreCasa) }}
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th width="15%">Fecha</th>
                            <th width="35%">Inquilino / Cuarto</th>
                            <th width="30%">Concepto</th>
                            <th width="20%" class="derecha">Monto Pagado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grupoPagos as $pago)
                            @php $filaNum++; @endphp
                            <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                                <td>{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="negrita">{{ $pago->recibo?->contrato?->inquilino?->inquilino ?? 'N/A' }}</span><br>
                                    <small style="color: #6c757d;">
                                        Cuarto: {{ $pago->recibo?->contrato?->cuarto?->cuarto ?? 'N/A' }}
                                    </small>
                                </td>
                                <td>{{ $pago->recibo?->adicionales['concepto'] ?? 'Recibo de Renta' }}</td>
                                <td class="derecha negrita" style="color: #198754;">${{ number_format($pago->montoPago, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="derecha negrita" style="font-size: 8pt; color: #495057;">Subtotal {{ $nombreCasa }}:</td>
                            <td class="derecha negrita" style="background: #f1f3f5; color: #198754; font-size: 9pt;">
                                ${{ number_format($grupoPagos->sum('montoPago'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @empty
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 15px;">No se registraron cobros en el rango de fechas seleccionado.</td>
                    </tr>
                </tbody>
            </table>
        @endforelse

        @if($pagosPorCasa->isNotEmpty())
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; page-break-inside: avoid;">
                <tfoot>
                    <tr>
                        <td width="80%" class="derecha negrita" style="font-size: 10pt;">GRAN TOTAL COBRADO:</td>
                        <td width="20%" class="derecha negrita" style="background: #198754; color: #ffffff; font-size: 11pt; padding: 6px;">
                            ${{ number_format($totalCobrado, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>

    <div style="position: fixed; bottom: 0; width: 100%; font-size: 7pt;" class="derecha">
        Página <span class="pagina"></span>
    </div>
</body>
</html>